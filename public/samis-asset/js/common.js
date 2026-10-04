document.addEventListener("DOMContentLoaded", function () {
    function formatCurrency(amount) {
        return new Intl.NumberFormat('sw-TZ', {
            style: 'currency',
            currency: 'TZS'
        }).format(amount);
    }

    window.validateInput = function (target) {
        if (!target.classList.contains("controlled")) return true;

        let name = target.getAttribute("name")?.replace(/\[\]/g, "") || "";
        let label = name.replace(/_/g, " ");
        let value = target.value.trim();

        // Container that holds the input and icons/messages
        let labelElement = target.closest("label") || target.parentElement;
        let errorElement = labelElement.querySelector(".error-message");
        let iconElement = labelElement.querySelector(".input-status-icon");

        // Create or reuse icon element
        if (!iconElement) {
            iconElement = document.createElement("span");
            iconElement.classList.add("input-status-icon");
            iconElement.style.position = "absolute";
            iconElement.style.right = "10px";
            iconElement.style.top = "50%";
            iconElement.style.transform = "translateY(-50%)";
            iconElement.style.fontSize = "16px";
            labelElement.style.position = "relative"; // to make absolute positioning work
            labelElement.appendChild(iconElement);
        }
        // // Additional validation for numeric fields
        //         if ((name === "cost_price" || name === "selling_price" ||
        //                 name === "quantity" || name === "reorder_level") &&
        //             value && isNaN(value)) {
        //             target.style.borderColor = "darkred";
        //             return false;
        //         }

        if (value !== "") {
            if (errorElement) errorElement.remove();
            if (target.type !== "checkbox" && target.type !== "radio") {
                iconElement.textContent = "✔";
            }
            iconElement.style.color = "green";
            target.style.borderColor = "green";
            return true;
        } else {
            if (!errorElement) {
                errorElement = document.createElement("span");
                errorElement.classList.add("error-message", "text-red");
                errorElement.style.color = "darkred";
                errorElement.style.display = "block";
                errorElement.style.marginTop = "4px";
                labelElement.appendChild(errorElement);
            }

            errorElement.textContent = target.tagName === "SELECT"
                ? `Please select a value for the ${label} field`
                : `The ${label} field is required`;
            iconElement.style.top = "35%";
            if (target.type !== "checkbox" && target.type !== "radio") {
                iconElement.textContent = "✖";
            }
            iconElement.style.color = "darkred";
            target.style.borderColor = "darkred";
            return false;
        }
    };

    document.addEventListener("change", function (event) {
        validateInput(event.target);
    });

    window.handleServerErrors = function (errors) {
        Object.keys(errors).forEach((field) => {
            let input = document.querySelector(`[name="${field}"]`);
            if (!input) return;

            let label = field.replace(/_/g, " ");
            let labelElement = input.closest("label") || input.parentElement;
            let errorElement = labelElement.querySelector(".error-message");

            if (!errorElement) {
                errorElement = document.createElement("span");
                errorElement.classList.add("error-message", "text-red");
                labelElement.appendChild(errorElement);
            }

            errorElement.textContent = errors[field]; // Show error message
            input.style.borderColor = "darkred";
        });
    }

    document.querySelectorAll(".form-control").forEach(input => {
        input.setAttribute("autocomplete", "off");
        input.setAttribute("autocorrect", "off");
        input.setAttribute("autocapitalize", "off");
        input.setAttribute("spellcheck", "false");
    });

    window.showToast = function (type, title, message) {
        const toast = {
            success: {
                icon: 'ti ti-check',
                class: 'bg-success'
            },
            error: {
                icon: 'ti ti-alert-triangle',
                class: 'bg-danger'
            },
            info: {
                icon: 'ti ti-info-circle',
                class: 'bg-info'
            },
            warning: {
                icon: 'ti ti-alert-circle',
                class: 'bg-warning'
            }
        };

        const options = {
            closeButton: true,
            debug: false,
            newestOnTop: true,
            progressBar: true,
            positionClass: "toast-top-right",
            preventDuplicates: false,
            onclick: null,
            showDuration: "300",
            hideDuration: "1000",
            timeOut: "5000",
            extendedTimeOut: "1000",
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut"
        };

        const toastType = toast[type] || toast.info;

        toastr[type](message, title, options);
    }

    window.groups = ["direct-cost", "tuition-fees", "fine", "penalty", "assets", "equity", "income", "expenses", 'liabilities', 'revenue'];
    window.categories = ["mandatory", "optional"];

    // Add these helper functions to Companies JS
    window.showLoader = function () {
        const loader = document.querySelector('#table-loader');
        if (loader) loader.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
    }
    window.hideLoader = function () {
        const loader = document.querySelector('#table-loader');
        if (loader) loader.innerHTML = '';
    }
    window.populateSelect = function (selector, data, defaultValue = '') {
        const select = document.querySelector(selector);
        // Clear existing options except the first one
        while (select.options.length > 1) {
            select.remove(1);
        }

        data.forEach(item => {
            const option = new Option(
                item.branch_name
                || item.company_name
                || item.name
                || item.title
                || item.account_name
                || item.short_name
                || item.location_name
                || item.municipal_name
                || item.region
                || item.vehicle_type_name
                || item.type_name
                || item.vehicle_number
                || item.municipal_id
                || `${item.staff ? (item.staff.first_name + " " + item.staff.last_name) : (item.username || `${item.first_name} ${item.last_name}`)}`
                , item.id || item.municipal_id || item.location_id || item.vehicle_type_id);
            select.add(option);
        });

        if (defaultValue) {
            select.value = defaultValue;
        }
    }

    // Helper function to format currency
    window.formatCurrency = function (amount) {
        return new Intl.NumberFormat('en-TZ', {
            style: 'currency',
            currency: 'TZS',
        }).format(amount);
    }

    window.formatDate = function (dateString) {
        let format = dateString.split('T')[0].split(' ')[0];
        const [year, month, day] = format.split('-');
        return `${day}/${month}/${year}`;
    }
    window.capitalizeFirstLetter = function (string) {
        if (!string) return "";
        return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
    }
    window.getPaymentOptionText = function (option) {
        switch (option) {
            case '1': return 'Full Payment';
            case '2': return 'Partial Payment';
            case '3': return 'Exact Amount';
            default: return 'Unknown';
        }
    }
    window.logoUrl = window.location.origin + '/samis-asset/image/smz.png';
    window.numberToWords = function (num) {
        if (num === 0) return "zero";

        const belowTwenty = [
            "", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten",
            "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen",
            "seventeen", "eighteen", "nineteen"
        ];
        const tens = [
            "", "", "twenty", "thirty", "forty", "fifty", "sixty", "seventy", "eighty", "ninety"
        ];
        const thousands = ["", "thousand", "million", "billion"];

        function helper(n) {
            if (n === 0) return "";
            else if (n < 20) return belowTwenty[n] + " ";
            else if (n < 100) return tens[Math.floor(n / 10)] + " " + helper(n % 10);
            else return belowTwenty[Math.floor(n / 100)] + " hundred " + helper(n % 100);
        }

        let word = "";
        let i = 0;

        while (num > 0) {
            if (num % 1000 !== 0) {
                word = helper(num % 1000) + thousands[i] + " " + word;
            }
            num = Math.floor(num / 1000);
            i++;
        }

        return word.trim();
    }
});
