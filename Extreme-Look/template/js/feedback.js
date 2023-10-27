$(document).ready(function () { //Мой код

    let rat1 = document.body.querySelector('#rating-feed-order'),
        arr = document.getElementsByClassName("rat"),
        th = document.body.querySelector('#thanks'),
        rerat = document.body.querySelector('#re-rat'),
        bt1 = document.body.querySelector('#bt1'),
        inputval = document.body.querySelector('#ratingvalue1'),
        bool = true, rep = true;

    let rat2 = document.body.querySelector('#rating-feed-liked'),
        arr2 = document.getElementsByClassName("ratl"),
        thl = document.body.querySelector('#thanksl'),
        reratl = document.body.querySelector('#re-ratl'),
        bt2 = document.body.querySelector('#bt2'),
        inputval2 = document.body.querySelector('#ratingvalue2'),
        bool2 = true, rep2 = true;

    $(rat1).click(function(event) {
        if(bool && rep) {
            let target = event.target;
            for (var i = 0; i < arr.length; i++) {
                arr[i].style.display = "none";
            }
            target.style.display = "";
            $(target).addClass("rating-feed-active");
            rat1.style.textAlign = "start";
            th.style.display = "block";
            rerat.style.display = "block";
            bt1.style.display = "none";
            $(inputval).val($(target).text());
            console.log($(inputval).val());
            bool = false;
            rep = false;
        }
        if(bool) rep = true;
    });

    $(rerat).click(function() {
        if(!bool){
            for (var i = 0; i < arr.length; i++) {
                arr[i].style.display = "block";
            }
            rat1.style.textAlign = "end";
            th.style.display = "none";
            rerat.style.display = "none";
            bt1.style.display = "";
            $(inputval).val('');
            $("div.rat.rating-feed-active").removeClass("rating-feed-active");
            bool = true;
        }

    })

    $(rat2).click(function(event) {
        if(bool2 && rep2) {
            let target = event.target;
            for (var i = 0; i < arr2.length; i++) {
                arr2[i].style.display = "none";
            }
            target.style.display = "";
            $(target).addClass("rating-feed-active");
            rat2.style.textAlign = "start";
            thl.style.display = "block";
            reratl.style.display = "block";
            bt2.style.display = "none";
            $(inputval2).val($(target).text());
            console.log($(inputval2).val());
            bool2 = false;
            rep2 = false;
        }
        if(bool2) rep2 = true;
    });

    $(reratl).click(function() {
        if(!bool2){
            for (var i = 0; i < arr2.length; i++) {
                arr2[i].style.display = "block";
            }
            rat2.style.textAlign = "end";
            thl.style.display = "none";
            reratl.style.display = "none";
            bt2.style.display = "";
            $(inputval2).val('');
            $("div.ratl.rating-feed-active").removeClass("rating-feed-active");
            bool2 = true;
        }

    })


    //console.log(localStorage.getItem("popupLastView"));
});