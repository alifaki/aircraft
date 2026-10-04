(() => {
'use strict';
const feedback=document.getElementById('av-feedback');
let busy=false;
function notify(message,level='success') {
 feedback.className='av-alerts av-'+(level==='warning'?'warning':level==='error'?'error':'success');
 feedback.textContent=message; feedback.scrollIntoView({block:'nearest',behavior:'smooth'});
}
const regionCodes='AD AE AF AG AI AL AM AO AQ AR AS AT AU AW AX AZ BA BB BD BE BF BG BH BI BJ BL BM BN BO BQ BR BS BT BV BW BY BZ CA CC CD CF CG CH CI CK CL CM CN CO CR CU CV CW CX CY CZ DE DJ DK DM DO DZ EC EE EG EH ER ES ET FI FJ FK FM FO FR GA GB GD GE GF GG GH GI GL GM GN GP GQ GR GS GT GU GW GY HK HM HN HR HT HU ID IE IL IM IN IO IQ IR IS IT JE JM JO JP KE KG KH KI KM KN KP KR KW KY KZ LA LB LC LI LK LR LS LT LU LV LY MA MC MD ME MF MG MH MK ML MM MN MO MP MQ MR MS MT MU MV MW MX MY MZ NA NC NE NF NG NI NL NO NP NR NU NZ OM PA PE PF PG PH PK PL PM PN PR PS PT PW PY QA RE RO RS RU RW SA SB SC SD SE SG SH SI SJ SK SL SM SN SO SR SS ST SV SX SY SZ TC TD TF TG TH TJ TK TL TM TN TO TR TT TV TW TZ UA UG UM US UY UZ VA VC VE VG VI VN VU WF WS YE YT ZA ZM ZW'.split(' ');
function compatibleRoles(select) {
 const category=select.selectedOptions[0]?.dataset.crewCategory;
 const role=select.form?.querySelector('select[name="role"]');if(!role||!category)return;
 Array.from(role.options).forEach(o=>o.disabled=category==='pilot'?o.value==='cabin_crew':o.value!=='cabin_crew');
 if(role.selectedOptions[0]?.disabled) role.value=Array.from(role.options).find(o=>!o.disabled)?.value||'';
 if(window.jQuery?.fn.select2) jQuery(role).trigger('change.select2');
}
function enhance() {
 document.querySelectorAll('#av-content select[data-countries]').forEach(select=>{
  const value=select.value;
  if(typeof Intl.DisplayNames==='function') {
   const names=new Intl.DisplayNames(['en'],{type:'region'});
   regionCodes.map(code=>names.of(code)).sort().forEach(name=>{
    if(!Array.from(select.options).some(o=>o.value===name)) select.add(new Option(name,name));
   });
  }
  select.value=value;
 });
 if(window.jQuery?.fn.select2) {
  jQuery('#av-content select').each(function(){
   if(!jQuery(this).hasClass('select2-hidden-accessible')) jQuery(this).select2({width:'100%',minimumResultsForSearch:8});
  });
 }
}
document.addEventListener('change',event=>{if(event.target.matches('select[name="crew_id"]'))compatibleRoles(event.target);});
if(window.jQuery) jQuery(document).on('change','select[name="crew_id"]',function(){compatibleRoles(this);});
function snapshot(skip) {
 const forms=[];
 document.querySelectorAll('#av-content form').forEach((form,index)=>{
  if(form===skip) return;
  forms.push({action:form.action,index,fields:Array.from(form.elements).filter(e=>e.name&&!['_token','_method'].includes(e.name)&&e.type!=='file').map(e=>({name:e.name,value:e.value,checked:e.checked,selected:e.tagName==='SELECT'?Array.from(e.selectedOptions).map(o=>o.value):null})),open:form.closest('details')?.open});
 });return forms;
}
async function refresh(url,skip=null) {
 const saved=snapshot(skip);
 const response=await fetch(url,{headers:{'X-Requested-With':'XMLHttpRequest','Accept':'text/html'},credentials:'same-origin'});
 if(!response.ok) throw new Error('Data saved, but the table could not refresh. Retry using Refresh table.');
 const doc=new DOMParser().parseFromString(await response.text(),'text/html');
 const fresh=doc.getElementById('av-content'); if(!fresh) throw new Error('Sign in again to refresh this page.');
 if(window.jQuery?.fn.select2) jQuery('#av-content select.select2-hidden-accessible').select2('destroy');
 document.getElementById('av-content').replaceChildren(...fresh.childNodes);
 const forms=Array.from(document.querySelectorAll('#av-content form'));
 saved.forEach(record=>{
  const form=forms.find((f,i)=>f.action===record.action&&i===record.index) || forms.find(f=>f.action===record.action);
  if(!form) return;
  record.fields.forEach(field=>{
   Array.from(form.elements).filter(e=>e.name===field.name).forEach(e=>{
    if(e.tagName==='SELECT') Array.from(e.options).forEach(o=>o.selected=field.selected?.includes(o.value));
    else {e.value=field.value;if(['checkbox','radio'].includes(e.type)) e.checked=field.checked;}
   });
  });if(record.open&&form.closest('details')) form.closest('details').open=true;
 });enhance();
}
document.addEventListener('submit',async event=>{
 const form=event.target;if(!form.closest('#av-content')||form.dataset.noAjax!==undefined||event.defaultPrevented) return;
 event.preventDefault();if(busy) return;if(!form.reportValidity()) return;
 busy=true;form.setAttribute('aria-busy','true');
 const button=event.submitter; const label=button?.textContent;
 if(button){button.disabled=true;button.textContent='Please wait…';}
 form.querySelectorAll('.av-field-error').forEach(e=>e.remove());form.querySelectorAll('.is-invalid').forEach(e=>e.classList.remove('is-invalid'));
 try {
  if(form.method.toLowerCase()==='get') {
   const url=new URL(form.action);url.search=new URLSearchParams(new FormData(form));await refresh(url);history.pushState({},'',url);
  } else {
   const response=await fetch(form.action,{method:'POST',body:new FormData(form),credentials:'same-origin',headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}});
   const data=await response.json().catch(()=>({message:response.status===419?'Session expired. Sign in again.':'The server could not complete this action.'}));
   if(!response.ok){
    const all=Object.values(data.errors||{}).flat();
    Object.entries(data.errors||{}).forEach(([name,messages])=>{
     const el=Array.from(form.elements).find(e=>e.name===name);if(!el)return;
     el.classList.add('is-invalid');const error=document.createElement('div');error.className='av-field-error';error.textContent=messages.flat().join(' ');el.parentElement.append(error);
    });throw new Error(all.length?all.join(' '):data.message);
   }
   notify(data.message||'Saved.',data.level);await refresh(location.href,form);
  }
 }catch(error){notify(error.message,'error');}
 finally {busy=false;form.removeAttribute('aria-busy');if(button){button.disabled=false;button.textContent=label;}}
});
document.addEventListener('click',async event=>{
 const link=event.target.closest('#av-content .pagination a');const button=event.target.closest('[data-refresh-table]');if(!link&&!button)return;
 event.preventDefault();if(busy)return;busy=true;document.getElementById('av-content').setAttribute('aria-busy','true');
 try{const url=link?.href||location.href;await refresh(url);if(link)history.pushState({},'',url);}catch(error){notify(error.message,'error');}finally{busy=false;document.getElementById('av-content').removeAttribute('aria-busy');}
});
window.addEventListener('popstate',()=>{if(!busy)refresh(location.href).catch(e=>notify(e.message,'error'));});
enhance();
})();
