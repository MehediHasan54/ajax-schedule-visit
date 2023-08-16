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
// // Get the current time
// var now = new Date();
// var currentHours = now.getHours();
// var currentMinutes = now.getMinutes();

// // Get the select element
// var select = document.getElementById("timeSlotSelect");

// // Loop through each option in the select element
// for (var i = 0; i < select.options.length; i++) {
//   var optionValue = select.options[i].value;
//   if (optionValue !== "") {
//     var optionTimeParts = optionValue.split(/:| /);
//     var optionHours = parseInt(optionTimeParts[0]);
//     var optionMinutes = parseInt(optionTimeParts[1]);

//     if (optionTimeParts[2] === "AM" && optionHours === 12) {
//       optionHours = 0;
//     } else if (optionTimeParts[2] === "PM" && optionHours !== 12) {
//       optionHours += 12;
//     }

//     // Compare the option time with the current time
//     if (optionHours < currentHours || (optionHours === currentHours && optionMinutes <= currentMinutes)) {
//       select.options[i].disabled = true; // Disable past time slots
//       select.options[i].style.backgroundColor = "#f2f2f2";
//     }
//   }
// }



  jQuery("#datepicker").datepicker({
    startDate: '+1d', // Disable today's date and previous dates
    daysOfWeekDisabled: [5, 6], // Disable weekend 
    datesDisabled: [ // Disable government holiday's
      '02/21/2023', 
      '03/08/2023', 
      '03/17/2023', 
      '03/26/2023', 
      '04/14/2023', 
      '04/19/2023', 
      '04/21/2023', 
      '04/20/2023', 
      '05/01/2023', 
      '05/04/2023', 
      '06/26/2023', 
      '07/17/2023', 
      '07/29/2023', 
      '08/15/2023', 
      '09/06/2023', 
      '09/27/2023', 
      '10/24/2023', 
      '12/16/2023', 
      '12/25/2023', 
    ],
    assumeNearbyYear: true
  });
 