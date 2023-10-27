$(document).ready(function () {
    //var post = {};
    $("#f-stor").click(function () {
/*        post['id'] = 1;
        post['user'] = user;
        post['devise'] = device;
        BX.ajax.post(
            '/bitrix/components/altop/stories.vlad/templates/.default/ajax/ajax.php',
            post,
            function (data) {
                console.log(data);
            }
        );*/
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
    $("#th-stor").click(function () {
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
});