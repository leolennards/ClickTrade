// Toggle the user profile box when the user icon is clicked
let userBtn = document.querySelector('#user-btn');
let profileDetail = document.querySelector('.profile-detail');

if(userBtn && profileDetail){
    userBtn.onclick = () => {
        profileDetail.classList.toggle('active');
    }
}

// Toggle the sidebar menu when the menu button is clicked
const toggle = document.querySelector(".toggle-btn");
toggle.addEventListener("click", function () {
  const sidebar = document.querySelector(".sidebar");
  sidebar.classList.toggle("active");
});
