// Wait for DOM to be fully loaded
document.addEventListener("DOMContentLoaded", function() {
    
    const body = document.querySelector("body");
    const modeToggle = body.querySelector(".mode-toggle");
    const sidebar = body.querySelector("nav");
    const sidebarToggle = body.querySelector(".sidebar-toggle");

    // Mobile sidebar elements
    const mobileSidebar = document.getElementById("sidebar");
    const mobileMenuButton = document.getElementById("mobile-menu-button");
    const sidebarOverlay = document.getElementById("sidebar-overlay");
    const closeSidebarButton = document.getElementById("close-sidebar");

    console.log("Mobile menu button:", mobileMenuButton); // Debug log
    console.log("Mobile sidebar:", mobileSidebar); // Debug log
    console.log("Sidebar overlay:", sidebarOverlay); // Debug log

    let getMode = localStorage.getItem("mode");
    if(getMode && getMode ==="dark"){
        body.classList.toggle("dark");
    }

    let getStatus = localStorage.getItem("status");
    if(getStatus && getStatus ==="close" && sidebar){
        sidebar.classList.toggle("close");
    }

    // Mode toggle functionality (existing)
    if (modeToggle) {
        modeToggle.addEventListener("click", () =>{
            body.classList.toggle("dark");
            if(body.classList.contains("dark")){
                localStorage.setItem("mode", "dark");
            }else{
                localStorage.setItem("mode", "light");
            }
        });
    }

    // Desktop sidebar toggle functionality (existing)
    if (sidebarToggle) {
        sidebarToggle.addEventListener("click", () => {
            sidebar.classList.toggle("close");
            if(sidebar.classList.contains("close")){
                localStorage.setItem("status", "close");
            }else{
                localStorage.setItem("status", "open");
            }
        });
    }

    // Mobile sidebar toggle functionality
    function toggleMobileSidebar() {
        console.log("Toggle mobile sidebar called"); // Debug log
        
        if (mobileSidebar && sidebarOverlay) {
            const isHidden = mobileSidebar.classList.contains("-translate-x-full");
            
            if (isHidden) {
                // Show sidebar
                mobileSidebar.classList.remove("-translate-x-full");
                sidebarOverlay.classList.remove("hidden");
                document.body.style.overflow = "hidden";
                console.log("Sidebar opened"); // Debug log
            } else {
                // Hide sidebar
                mobileSidebar.classList.add("-translate-x-full");
                sidebarOverlay.classList.add("hidden");
                document.body.style.overflow = "";
                console.log("Sidebar closed"); // Debug log
            }
        }
    }

    // Mobile menu button click
    if (mobileMenuButton) {
        mobileMenuButton.addEventListener("click", function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log("Mobile menu button clicked"); // Debug log
            toggleMobileSidebar();
        });
        console.log("Mobile menu button event listener added"); // Debug log
    } else {
        console.log("Mobile menu button not found!"); // Debug log
    }

    // Close sidebar button click
    if (closeSidebarButton) {
        closeSidebarButton.addEventListener("click", function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log("Close sidebar button clicked"); // Debug log
            toggleMobileSidebar();
        });
    }

    // Sidebar overlay click (close sidebar)
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener("click", function(e) {
            e.preventDefault();
            console.log("Overlay clicked"); // Debug log
            toggleMobileSidebar();
        });
    }

    // Close mobile sidebar when clicking on sidebar links
    if (mobileSidebar) {
        const sidebarLinks = mobileSidebar.querySelectorAll("a");
        sidebarLinks.forEach(link => {
            link.addEventListener("click", () => {
                // Only close on mobile
                if (window.innerWidth < 768) {
                    setTimeout(() => {
                        toggleMobileSidebar();
                    }, 100); // Small delay to allow navigation
                }
            });
        });
    }

    // Handle window resize
    window.addEventListener("resize", () => {
        if (window.innerWidth >= 768) {
            // Desktop view - ensure sidebar is hidden on mobile and body scroll is enabled
            if (mobileSidebar) {
                mobileSidebar.classList.add("-translate-x-full");
            }
            if (sidebarOverlay) {
                sidebarOverlay.classList.add("hidden");
            }
            document.body.style.overflow = "";
        }
    });

    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
