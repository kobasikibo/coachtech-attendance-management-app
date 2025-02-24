document.addEventListener("DOMContentLoaded", function () {
    const tabLinks = document.querySelectorAll(".tab-link");

    tabLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            e.preventDefault(); // デフォルトのリンク動作を無効にする

            tabLinks.forEach((link) => {
                link.classList.remove("active");
            });

            this.classList.add("active");

            const tab = new URL(this.href).searchParams.get("tab");

            window.location.href = `/stamp_correction_request/list?tab=${tab}`;
        });
    });
});
