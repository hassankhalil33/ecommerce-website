const wishlistsEl = document.getElementById("wishlists");
let localStorageData = JSON.parse(localStorage.getItem("auth"));
// console.log(localStorageData);
axios({
  method: "POST",
  url: "http://localhost/jacht/authorized.php",
  data: {
    userName: localStorageData[1],
    token: localStorageData[2],
  },
  headers: { "Content-Type": "multipart/form-data" },
})
  .then(function (response) {
    //handle success
    if (response.data === true) {
      fetchWishlistData(localStorageData[0]);
    } else {
      localStorage.clear();
      window.location.href = "login.html";
    }
  })
  .catch(function (response) {
    //handle error
    console.log(response);
  });

const fetchWishlistData = (id) => {
  axios({
    method: "POST",
    url: "http://localhost/jacht/view_wishlist.php",
    data: {
      user_id: id,
    },
    headers: { "Content-Type": "multipart/form-data" },
  })
    .then(function (response) {
      //handle success
      console.log(response.data);
      fillData(response.data);
    })
    .catch(function (response) {
      //handle error
      console.log(response);
    });
};

const fillData = (data) => {
  //   console.log(data[0]);
  const wishlistArr = [];
  for (let i = 0; i < data.length; i++) {
    console.log(data);
    let wishlistHtml = `        <div class="wishlist">
                <div class="wishlist-details">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-trash-2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        <line x1="10" y1="11" x2="10" y2="17"></line>
                        <line x1="14" y1="11" x2="14" y2="17"></line>
                    </svg>
                    <img src="./assets/Screenshot from 2022-09-22 09-45-55.png" alt="">
                </div>
                <p class="wishlist-name">${data[i]["name"]}</p>
                <p class="wishlist-price">$${data[i]["price"]}</p>
                <p class="wishlist-status">In Stock</p>
                <div class="wishlist-cart">
                    <p>Added on Dec 5, 2021</p>
                    <button>Add to cart</button>
                </div>
            </div>`;
    wishlistArr.push(wishlistHtml);
  }
  console.log(wishlistArr);
  wishlistsEl.innerHTML = wishlistArr.join("");
};
