$(document).ready(function () {
    $("#f-stor").click(function () {
        $(".stori").append($('<iframe>').attr("frameborder", "no")
            .attr("src", "bitrix/components/altop/stories.vlad/templates/.default/stories1.php")
            .attr("width", "100%")
            .attr("height", "100%")
            .attr("id", "frame-storis")
            .text("Ваш браузер не поддерживает плавающие фреймы!")
        ).addClass('active-storis');
    });
    $("#s-stor").click(function () {
        $(".stori").append($('<iframe>').attr("frameborder", "no")
            .attr("src", "bitrix/components/altop/stories.vlad/templates/.default/stories2.php")
            .attr("width", "100%")
            .attr("height", "100%")
            .attr("id", "frame-storis")
            .text("Ваш браузер не поддерживает плавающие фреймы!")
        ).addClass('active-storis');
    });
    $("#t-stor").click(function () {
        $(".stori").append($('<iframe>').attr("frameborder", "no")
            .attr("src", "bitrix/components/altop/stories.vlad/templates/.default/stories3.php")
            .attr("width", "100%")
            .attr("height", "100%")
            .attr("id", "frame-storis")
            .text("Ваш браузер не поддерживает плавающие фреймы!")
        ).addClass('active-storis');
    });
    $("#fo-stor").click(function () {
        $(".stori").append($('<iframe>').attr("frameborder", "no")
            .attr("src", "bitrix/components/altop/stories.vlad/templates/.default/stories4.php")
            .attr("width", "100%")
            .attr("height", "100%")
            .attr("id", "frame-storis")
            .text("Ваш браузер не поддерживает плавающие фреймы!")
        ).addClass('active-storis');
    });
    $("#fi-stor").click(function () {
        $(".stori").append($('<iframe>').attr("frameborder", "no")
            .attr("src", "bitrix/components/altop/stories.vlad/templates/.default/stories5.php")
            .attr("width", "100%")
            .attr("height", "100%")
            .attr("id", "frame-storis")
            .text("Ваш браузер не поддерживает плавающие фреймы!")
        ).addClass('active-storis');
    });
    $('.stori').click(function () {
        $(this).removeClass('active-storis');
    })
});