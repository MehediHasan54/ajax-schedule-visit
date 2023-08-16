function toggleDropdown() {

    const overlay = document.querySelector('.dropdown_timeselect_overlay'); 
    overlay.style.display = overlay.style.display === 'block' ? 'none' : 'block';

    const dropdownList = document.querySelector('.select_time_slot_hd');
    dropdownList.style.display = dropdownList.style.display === 'block' ? 'none' : 'block';
  
    const arrow = document.querySelector('.arrow');
    //arrow.style.transform = arrow.style.transform === 'rotate(180deg)' ? 'rotate(0deg)' : 'rotate(180deg)';
  }
  
  function selectTime(time) {
    const selectedTimeInput = document.getElementById('selectedTime');
    selectedTimeInput.value = time;
  
    const dropdownHeader = document.querySelector('.dropdown-header');
    dropdownHeader.innerHTML = time + '<i class="arrow"></i>';
  
    toggleDropdown();
  }
  const items = document.querySelectorAll(".custom-dropdown ul li");
  
  document.addEventListener("DOMContentLoaded", function() {
    const items = document.querySelectorAll(".custom-dropdown ul li");
    
    items.forEach(item => {
      item.addEventListener("click", function () {
        items.forEach(item => {
          item.classList.remove("active");
        });
        this.classList.add("active");
      });
    });
 
  
  });