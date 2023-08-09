// past date disable

// var date = new Date();
// var todaysdate = date.getDate();
// var month = date.getMonth() + 1;
// if(todaysdate < 10 ){
//     todaysdate = "0" + todaysdate;
// }
// if(month < 10 ){
//     month = "0" + month;
// }

// var year = date.getUTCFullYear();
// var minDate = year + '-' + month + '-' + todaysdate;

// document.getElementById("mdatepicker").setAttribute('min',minDate);

var initialcountry = sessionStorage.getItem("countrycode");
//console.log(initialcountry);
var input = document.querySelector("#phone");

window.intlTelInput(input, {
  // autoPlaceholder: true,
  initialCountry: initialcountry,
  geoIpLookup: function (callback) {
    $.get("http://ipinfo.io", function () {}, "jsonp").always(function (resp) {
      var countryCode = resp && resp.country ? resp.country : "";
      callback(countryCode);
    });
  },
  hiddenInput: "mobile_number",
  separateDialCode: true,
  utilsScript:
    "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.js",
});

//console.log($clienttimezone);
var iti = window.intlTelInputGlobals.getInstance(input);
var countryData = iti.getSelectedCountryData();
var callingCode = countryData["dialCode"];

//console.log($client);
document.getElementById("country_code").value = countryData.iso2.toUpperCase();
document.getElementById("calling_code").value = callingCode;

input.addEventListener("countrychange", function () {
  callingCode = iti.getSelectedCountryData()["dialCode"];
  var iso2 = iti.getSelectedCountryData().iso2;
  document.getElementById("country_code").value = iso2.toUpperCase();
  document.getElementById("calling_code").value = callingCode;
});

function backToPrevious(e) {
  jQuery(".modal-header h3").show();
  jQuery(".modal-header p").show();
  jQuery(".schedule-msg").hide();
  jQuery("#topForm").show();
  $(".text-field").val("");
  $("#quickContactModal").modal("hide");
}

/*
// Get the modal
var modal = document.getElementById('quickContactModal');
// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
*/

function loadTimeZoneList() {
  let select = document.getElementById("dropdownTimeZone");
  select.innerHTML = "";
  let browserTimeZone = moment.tz.guess();
  let timeZones = moment.tz.names();
  timeZones.forEach((timeZone) => {
    option = document.createElement("option");
    option.textContent = `${timeZone} (GMT${moment.tz(timeZone).format("Z")})`;
    option.value = timeZone;
    if (timeZone == browserTimeZone) {
      option.selected = true;
    }
    select.appendChild(option);
  });
}

jQuery(function ($) {
  $(".btn-close").on("click", function () {
    $(".schedule-msg").hide();
    $("#topForm").show();
    $(".text-field").val("");
    $.modal.close();
  });
  $(".submit_btn").hide();
  $(".submit_btn1").show();
  $("#topForm").submit(function (e) {
    e.preventDefault();
    $(".schedule-msg").show();
    $(".schedule-msg").html(
      '<img src="' +
        ajax_schedule.directory_url +
        '/images/loading.gif" style="display: block; margin: 0 auto; width: 30px;">'
    );
    $(".submit_btn").show();
    $(".submit_btn1").hide();
    $.post(
      ajax_schedule.ajaxurl,
      {
        action: "add_schedule",
        data: $("#topForm").serialize(),
        dataType: "json",
      },
      function (schedule) {
        var json_schedule = $.parseJSON(schedule);

        if (json_schedule.status == true) {
          var success = json_schedule.desc;
          $(".schedule-msg").show();
          $("#schedule-desc").hide();
          $(".modal-header h3").hide();
          $(".modal-header p").hide();
          $(".submit_btn").hide();
          $(".submit_btn1").show();
          //$('#topForm').hide();
          if (success != "") {
            if (
              window.location.href == ""
            )
              window.location.href =
                "";
            else {
              $("#topForm").hide();
              $(".schedule-msg")
                .css({ "text-align": "center", "font-size": "15px" })
                .html("")
                .html(success);
            }
          }

          $(".text-field").val("");
        } else {
          var error = json_schedule.desc;
          var type = json_schedule.type;
          $(".schedule-msg").show();
          $("#schedule-desc").hide();

          if (error != "" && type == "duplicate") {
            $(".modal-header h3").hide();
            $(".modal-header p").hide();
            $("#topForm").hide();
            $(".submit_btn").show();
            $(".submit_btn1").hide();
          }
          //$('#topForm').hide();
          $(".schedule-msg").html("").html(error);
          $(".submit_btn").hide();
          $(".submit_btn1").show();
        }
      }
    );
  });
});

jQuery("#datepicker").datepicker({
  startDate: new Date(),
});
