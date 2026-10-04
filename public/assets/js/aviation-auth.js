(() => {
  const token = document.querySelector('meta[name="csrf-token"]')?.content;
  const submit = async (form, message, button) => {
    if (button) button.disabled = true;
    message.className = 'auth-message';
    message.textContent = '';
    try {
      const response = await fetch(form.action, {
        method: 'POST',
        headers: {'Accept':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':token},
        body: new FormData(form)
      });
      const result = await response.json();
      if (!response.ok || !result.success) {
        const first = result.errors && (Array.isArray(result.errors) ? result.errors[0] : Object.values(result.errors).flat()[0]);
        throw new Error(result.message || first || 'Please check your details and try again.');
      }
      message.className = 'auth-message is-success';
      message.textContent = result.message || 'Request completed successfully.';
      if (result.data?.redirect) {
        window.location.assign(result.data.redirect);
        return true;
      }
      return true;
    } catch (error) {
      message.className = 'auth-message is-error';
      message.textContent = error.message === 'Unexpected token <' ? 'The server is unavailable. Please try again.' : (error.message || 'The request could not be completed.');
      return false;
    } finally {
      if (button) button.disabled = false;
    }
  };
  document.querySelectorAll('[data-auth-form]').forEach(form => {
    form.addEventListener('submit', event => {
      event.preventDefault();
      if (!form.reportValidity()) return;
      submit(form, form.querySelector('[data-auth-message]'), form.querySelector('[type="submit"]'));
    });
  });
  document.querySelectorAll('[data-password-toggle]').forEach(button => {
    button.addEventListener('click', () => {
      const input = document.getElementById(button.dataset.passwordToggle);
      if (!input) return;
      input.type = input.type === 'password' ? 'text' : 'password';
      button.textContent = input.type === 'password' ? 'Show' : 'Hide';
      button.setAttribute('aria-label', button.textContent + ' password');
    });
  });
  document.querySelectorAll('[data-auth-resend]').forEach(form => {
    const button = form.querySelector('button'), countdown = form.querySelector('[data-countdown]');
    let timer;
    const start = () => {
      let remaining = Number(form.dataset.resendDelay || 300);
      button.disabled = true;
      const tick = () => {
        if (remaining <= 0) {clearInterval(timer);button.disabled = false;countdown.textContent = 'You may request a new code.';return;}
        const minutes = String(Math.floor(remaining / 60)).padStart(2,'0');
        const seconds = String(remaining % 60).padStart(2,'0');
        countdown.textContent = 'Resend available in ' + minutes + ':' + seconds;
        remaining--;
      };
      tick();
      timer = setInterval(tick,1000);
    };
    start();
    form.addEventListener('submit', async event => {
      event.preventDefault();
      const message = document.querySelector('[data-auth-form] [data-auth-message]');
      if (await submit(form,message,button)) {clearInterval(timer);start();}
    });
  });
})();
