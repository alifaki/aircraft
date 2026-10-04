document.addEventListener("DOMContentLoaded", function () {
    function loadState() {
        let activeSidebar = localStorage.getItem("activeSidebar");
        let activeSubItemIndex = localStorage.getItem("activeSubItemIndex");

        if (activeSidebar) {
            document.querySelectorAll('.sidebar-link').forEach(link => {
                if (link.textContent.trim() === activeSidebar) {
                    link.classList.add("active");

                    if (link.classList.contains("has-arrow")) {
                        link.setAttribute("aria-expanded", "true");
                        let submenu = link.nextElementSibling;
                        if (submenu && submenu.classList.contains("collapse")) {
                            submenu.classList.add("show", "in");
                        }
                    }
                }
            });
        }

        if (activeSubItemIndex !== null) {
            let subLinks = document.querySelectorAll('.collapse .sidebar-link');
            if (subLinks[activeSubItemIndex]) {
                subLinks[activeSubItemIndex].classList.add("active");
            }
        }
    }

    function resetAll() {
        document.querySelectorAll(".sidebar-link").forEach(link => {
            link.classList.remove("active");
            link.setAttribute("aria-expanded", "false");
        });

        document.querySelectorAll("ul.collapse").forEach(ul => {
            ul.classList.remove("show", "in");
        });
    }

    // Handle parent menu item clicks (has-arrow)
    document.querySelectorAll("li.sidebar-item > a.sidebar-link.has-arrow").forEach(link => {
        link.addEventListener("click", function (event) {
            event.preventDefault();

            // If already active, collapse it
            if (this.classList.contains("active")) {
                // Close this menu only
                this.classList.remove("active");
                this.setAttribute("aria-expanded", "false");
                let submenu = this.nextElementSibling;
                if (submenu && submenu.classList.contains("collapse")) {
                    submenu.classList.remove("show", "in");
                }
                localStorage.removeItem("activeSidebar");
                localStorage.removeItem("activeSubItemIndex");
                return;
            }

            // Reset all items (no-arrow and has-arrow)
            resetAll();

            // Activate clicked menu
            this.classList.add("active");
            this.setAttribute("aria-expanded", "true");

            let submenu = this.nextElementSibling;
            if (submenu && submenu.classList.contains("collapse")) {
                submenu.classList.add("show", "in");
            }

            localStorage.setItem("activeSidebar", this.textContent.trim());
            localStorage.removeItem("activeSubItemIndex");
        });
    });

    // Handle submenu item clicks (without affecting parent collapse)
    document.querySelectorAll("ul.collapse .sidebar-link").forEach((link, index) => {
        link.addEventListener("click", function (event) {
            // event.preventDefault();
            event.stopPropagation();

            document.querySelectorAll("ul.collapse .sidebar-link").forEach(item => {
                item.classList.remove("active");
            });

            this.classList.add("active");

            localStorage.setItem("activeSubItemIndex", index);
        });
    });

    // Handle menu item clicks without submenus (no-arrow)
    document.querySelectorAll(".sidebar-link.no-arrow").forEach(link => {
        link.addEventListener("click", function (event) {
            // event.preventDefault();

            // Reset all (no-arrow and has-arrow)
            resetAll();

            // Activate clicked item
            this.classList.add("active");

            localStorage.setItem("activeSidebar", this.textContent.trim());
        });
    });

    // Load state on page load
    loadState();
});
