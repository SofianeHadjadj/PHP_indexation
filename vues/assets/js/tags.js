    $(document).ready(function(){
      $("div").click(function(){
        var id = $(this).attr('id');
        if (typeof id !== typeof undefined && id !== false) {
          var textid = id.substring(0, 5);
          var numid = id.substring(5, 6);
          var swid = "#sw"+numid;
          var ctid = "#ct"+numid;
          var nuage = "div#cloud"+numid+" > div.word > span#scloud"
          if (textid == "cloud") {
            $(nuage).fadeOut(function () {
                $(nuage).text(($(nuage).text() == '+') ? '-' : '+').fadeIn();
            })
            if ($(ctid).hasClass("hide")) {
              $(ctid).removeClass("hide");
              $(ctid).addClass("show");
            }
            else if ($(ctid).hasClass("show")) {
              $(ctid).removeClass("show");
              $(ctid).addClass("hide");
            }          
          }
        }
      });
    }); 