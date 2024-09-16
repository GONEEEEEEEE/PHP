$(document).ready(function () {
    var height = $(window).height() - $("#footer-wp").outerHeight(true) - $("#header-wp").outerHeight(true);
    $("#content").css("min-height", height);

    //  CHECK ALL
    $('input[name="checkAll"]').click(function () {
        var status = $(this).prop("checked");
        $('.list-table-wp tbody tr td input[type="checkbox"]').prop("checked", status);
    });

    // EVENT SIDEBAR MENU
    $("#sidebar-menu .nav-item .nav-link .title").after('<span class="fa fa-angle-right arrow"></span>');
    var sidebar_menu = $("#sidebar-menu > .nav-item > .nav-link");
    sidebar_menu.on("click", function () {
        if (!$(this).parent("li").hasClass("active")) {
            $(".sub-menu").slideUp();
            $(this).parent("li").find(".sub-menu").slideDown();
            $("#sidebar-menu > .nav-item").removeClass("active");
            $(this).parent("li").addClass("active");
            return false;
        } else {
            $(".sub-menu").slideUp();
            $("#sidebar-menu > .nav-item").removeClass("active");
            return false;
        }
    });

    

    //=========================================
    //MESSAGES CHANGPASS
    //=========================================

    // $("#btn-submit").click(function (e) {
    //     e.preventDefault();
    //     var pass_old = $("#pass-old").val();
    //     var pass_new = $("#pass-new").val();
    //     var confirm_pass = $("#confirm-pass").val();

    //     var data = { pass_old: pass_old, pass_new: pass_new, confirm_pass: confirm_pass };
    //     $.ajax({
    //         type: "POST",
    //         url: "?mod=users&action=pass",
    //         data: data,
    //         dataType: "json",
    //         success: function (data) {
    //             var check = "";
    //             if (data.hasOwnProperty("pass")) {
    //                 check += data.pass + "<br>";
    //             }
    //             if (data.hasOwnProperty("pass_old")) {
    //                 check += data.pass_old + "<br>";
    //             }

    //             if (data.hasOwnProperty("pass_new")) {
    //                 check += data.pass_new + "<br>";
    //             }

    //             if (data.hasOwnProperty("confirm_pass")) {
    //                 check += data.confirm_pass + "<br>";
    //             }

    //             showErrorToast(check);
    //         },
    //     });
    //     // console.log(data);
    // });
});
