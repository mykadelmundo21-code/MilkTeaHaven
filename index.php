<?php
session_start();

$usersFile = "users.json";

if (!file_exists($usersFile)) {
    file_put_contents($usersFile, json_encode([]));
}

$users = json_decode(file_get_contents($usersFile), true);

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    foreach ($users as $u) {
        if (strtolower($u['username']) == strtolower($username)) {
            $error = "Username already exists!";
        }
    }

    if (!isset($error)) {
        $users[] = ["username"=>$username,"password"=>$password];
        file_put_contents($usersFile, json_encode($users));
        $_SESSION['user'] = $username;
        header("Location: index.php");
        exit;
    }
}

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (strtolower($username) == "admin") {
        $_SESSION['user'] = "admin";
        header("Location: index.php");
        exit;
    }

    foreach ($users as $u) {
        if ($u['username'] == $username && $u['password'] == $password) {
            $_SESSION['user'] = $username;
            header("Location: index.php");
            exit;
        }
    }

    $error = "Invalid username or password!";
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$isLoggedIn = isset($_SESSION['user']);
$isAdmin = $isLoggedIn && strtolower($_SESSION['user']) == "admin";
?>
<!DOCTYPE html>
<html>
<head>
<title>Milk Tea Haven</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
*{
    box-sizing:border-box;
    font-family:'Segoe UI',Arial,sans-serif;
}
body{
    margin:0;
    background:
        radial-gradient(circle at top left, rgba(255,239,204,.75), transparent 35%),
        linear-gradient(135deg,#f6ead7,#e8d0aa,#c89b63);
    color:#2b1a10;
}
.orange{color:#8b4a1f}
.btn{
    background:linear-gradient(135deg,#8b4a1f,#b87538);
    color:#fff;
    border:0;
    border-radius:12px;
    padding:13px 25px;
    font-weight:bold;
    cursor:pointer;
    box-shadow:0 8px 18px rgba(86,45,18,.25);
}
.btn:hover{
    background:linear-gradient(135deg,#6f3615,#a8642d);
}
.gray{
    background:#d8c0a0;
    color:#3b2414;
}
.card{
    background:rgba(255,248,235,.96);
    border:1px solid rgba(120,70,30,.18);
    border-radius:20px;
    padding:28px;
    box-shadow:0 12px 28px rgba(60,32,12,.18);
    transition:.25s;
}
.card:hover{
    transform:translateY(-5px);
    box-shadow:0 18px 35px rgba(60,32,12,.25);
}

@keyframes productDance{
    0%,100%{transform:translateY(0) rotate(0deg)}
    25%{transform:translateY(-5px) rotate(-1deg)}
    50%{transform:translateY(0) rotate(1deg)}
    75%{transform:translateY(-3px) rotate(-1deg)}
}
.product:hover{
    animation:productDance .6s ease-in-out infinite;
}

/* LOGIN */
.login-page{
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:20px;
    background:
        linear-gradient(rgba(64,34,14,.45),rgba(64,34,14,.45)),
        linear-gradient(135deg,#3a1f0f,#7b4a24,#c89255);
}
.login-box{
    width:430px;
    text-align:center;
    background:rgba(255,248,235,.96);
    border:1px solid rgba(255,255,255,.35);
    border-radius:28px;
    padding:45px 35px;
    box-shadow:0 22px 50px rgba(35,18,7,.35);
}
.logo{
    font-size:48px;
    background:linear-gradient(135deg,#7a3f18,#c18445);
    color:white;
    border-radius:50%;
    width:95px;
    height:95px;
    margin:0 auto 25px;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow:0 15px 35px rgba(88,45,18,.35);
}
.login-title{
    color:#5b2e12;
    font-size:34px;
    margin:0;
}
.login-subtitle{
    color:#705236;
    margin-bottom:20px;
}
.admin-hint{
    background:#f8ead2;
    border:1px dashed #9b5a25;
    padding:10px;
    border-radius:12px;
    margin-bottom:22px;
    color:#5b2e12;
    font-weight:bold;
}
.login-field{
    text-align:left;
    margin-bottom:12px;
}
.login-field label{
    font-weight:bold;
    color:#4b2c17;
}
.input-wrap{
    position:relative;
}
.input-icon{
    position:absolute;
    left:16px;
    top:23px;
    color:#9b7b5a;
    font-size:20px;
}
.login-field input{
    padding-left:48px;
}
.login-btn{
    width:100%;
    background:linear-gradient(135deg,#8b4a1f,#c18445);
    color:white;
    border:0;
    padding:16px;
    border-radius:12px;
    font-size:17px;
    font-weight:bold;
    cursor:pointer;
}
.login-icons{
    margin-top:25px;
    font-size:30px;
    letter-spacing:15px;
}

input,textarea,select{
    width:100%;
    padding:16px;
    border:1px solid #c9a77f;
    background:#fffaf1;
    color:#2b1a10;
    border-radius:10px;
    font-size:16px;
    margin:8px 0 20px;
}
input:focus,textarea:focus,select:focus{
    outline:none;
    border-color:#8b4a1f;
    box-shadow:0 0 0 3px rgba(139,74,31,.15);
}

/* HEADER */
.header{
    background:rgba(255,248,235,.96);
    padding:20px 9%;
    box-shadow:0 3px 14px rgba(60,32,12,.18);
    position:sticky;
    top:0;
    z-index:5;
    border-bottom:1px solid rgba(113,67,29,.15);
}
.top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:15px;
}
.brand{
    display:flex;
    gap:15px;
    align-items:center;
}
.brand h1{
    margin:0;
    color:#5b2e12;
}
.brand span{
    color:#7a5a3a;
}
.header-action{
    border:0;
    background:#f4e5cd;
    color:#4b2c17;
    font-weight:bold;
    padding:10px 14px;
    border-radius:18px;
    cursor:pointer;
    margin:3px;
}
.user-badge{
    margin-left:10px;
    background:#f4e5cd;
    padding:8px 15px;
    border-radius:20px;
    font-weight:bold;
    display:inline-block;
}
.logout-link{
    font-size:28px;
    text-decoration:none;
    margin-left:15px;
    color:#5b2e12;
}
.search-row{
    display:flex;
    gap:15px;
    margin-top:18px;
}
.search{
    flex:1;
}
.cat-btn{
    background:#ead8bd;
    color:#4b2c17;
    border:0;
    border-radius:12px;
    padding:14px 22px;
    font-weight:bold;
    cursor:pointer;
}
.cat-btn.active{
    background:linear-gradient(135deg,#6b3515,#b87538);
    color:white;
}

/* MAIN */
.container{
    padding:40px 9%;
}
.promo-banner{
    background:#fff8ea;
    border:2px dashed #8b4a1f;
    border-radius:15px;
    padding:15px 20px;
    margin-bottom:25px;
    color:#5b2e12;
    font-weight:bold;
}
.popular{
    background:
        linear-gradient(rgba(93,49,19,.78),rgba(93,49,19,.78)),
        linear-gradient(135deg,#8b4a1f,#c18445,#e3b06b);
    border-radius:20px;
    padding:25px;
    color:white;
    margin-bottom:40px;
    box-shadow:0 14px 30px rgba(60,32,12,.25);
}
.popular-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
}
.popular-item{
    background:rgba(255,255,255,.18);
    border:1px solid rgba(255,255,255,.25);
    border-radius:14px;
    padding:25px;
    text-align:center;
}
.title-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.title-row h1{
    color:#5b2e12;
}
.grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:28px;
}
.product{
    min-height:350px;
    position:relative;
}
.product .emoji{
    font-size:55px;
    text-align:center;
}
.badge{
    background:#6f3615;
    color:white;
    border-radius:20px;
    padding:7px 12px;
    font-size:12px;
    display:inline-block;
}
.price{
    font-size:26px;
    color:#7a3f18;
    font-weight:bold;
}
.add{
    float:right;
}
.fav-btn{
    position:absolute;
    top:20px;
    right:20px;
    border:0;
    background:#f1ddbf;
    color:#7a3f18;
    border-radius:50%;
    width:38px;
    height:38px;
    font-size:20px;
    cursor:pointer;
}
.fav-btn.active{
    background:#9b2f1f;
    color:white;
}
.rating{
    color:#b87538;
    margin:8px 0;
}

/* MODAL */
.modal{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(38,20,8,.72);
    z-index:20;
    align-items:center;
    justify-content:center;
    overflow-y:auto;
    padding:20px;
}
.modal-box{
    background:#fff8ea;
    width:500px;
    max-width:90%;
    border-radius:22px;
    padding:30px;
    max-height:90vh;
    overflow-y:auto;
    box-shadow:0 25px 60px rgba(40,20,5,.45);
    border:1px solid rgba(120,70,30,.2);
}
.custom-scroll{
    max-height:90vh;
    overflow-y:auto;
}
.close{
    float:right;
    font-size:30px;
    cursor:pointer;
    color:#5b2e12;
}
.cart-product{
    background:#f4e5cd;
    border-radius:14px;
    padding:18px;
    margin-bottom:15px;
    display:grid;
    grid-template-columns:60px 1fr auto;
    gap:15px;
    align-items:center;
}
.cart-emoji{
    font-size:42px;
    text-align:center;
}
.qty-btn{
    background:#c7a27a;
    color:#2b1a10;
    border:0;
    padding:4px 10px;
    border-radius:5px;
    font-size:18px;
    cursor:pointer;
}
.qty-num{
    margin:0 15px;
    font-weight:bold;
}
.small-price{
    color:#7a3f18;
    font-weight:bold;
}
.total,.custom-total{
    background:#f4e5cd;
    border-radius:12px;
    padding:18px;
    display:flex;
    justify-content:space-between;
    font-size:20px;
}

.option-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:10px;
    margin-bottom:25px;
}
.option-btn{
    background:#fffaf1;
    border:2px solid #d5b58f;
    padding:14px;
    border-radius:12px;
    font-size:17px;
    font-weight:bold;
    cursor:pointer;
}
.option-btn.active{
    border-color:#8b4a1f;
    color:#6b3515;
    background:#f3dfc0;
}
.topping-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:10px;
    margin-bottom:20px;
}
.topping-btn{
    padding:12px;
    border:2px solid #d5b58f;
    border-radius:10px;
    background:#fffaf1;
    cursor:pointer;
    font-weight:bold;
}
.topping-btn.active{
    border-color:#8b4a1f;
    color:#6b3515;
    background:#f3dfc0;
}
.toast{
    position:fixed;
    right:25px;
    bottom:25px;
    background:#3b2111;
    color:white;
    padding:15px 20px;
    border-radius:12px;
    display:none;
    z-index:99;
    box-shadow:0 10px 25px rgba(0,0,0,.35);
}
.progress{
    height:12px;
    background:#ead8bd;
    border-radius:20px;
    overflow:hidden;
}
.progress-bar{
    height:100%;
    background:linear-gradient(90deg,#6b3515,#b87538,#e3b06b);
    width:25%;
    transition:.3s;
}
.status-badge{
    float:right;
    padding:8px 14px;
    border-radius:20px;
    font-weight:bold;
}

/* ADMIN */
.admin-body{
    background:
        linear-gradient(rgba(61,32,12,.62),rgba(61,32,12,.62)),
        linear-gradient(135deg,#3a1f0f,#7b4a24,#c89255);
    min-height:100vh;
    padding:35px 9%;
}
.admin-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    color:white;
    margin-bottom:30px;
}
.admin-title h1{
    margin:0;
    font-size:38px;
}
.admin-card{
    background:#fff8ea;
    border-radius:22px;
    padding:25px;
    box-shadow:0 12px 35px rgba(35,18,7,.28);
}
.stats-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
    margin-bottom:25px;
}
.stat{
    border-radius:18px;
    padding:22px;
    color:white;
    box-shadow:0 10px 25px rgba(0,0,0,.18);
}
.stat h2{
    margin:0;
    font-size:32px;
}
.pending{background:#b87538}
.preparing{background:#8b5a2b}
.delivery{background:#6b3515}
.revenue{background:#4f6f2f}
.admin-tabs{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    margin:25px 0;
}
.admin-tab{
    border:0;
    border-radius:12px;
    padding:12px 18px;
    font-weight:bold;
    cursor:pointer;
    background:#ead8bd;
    color:#4b2c17;
}
.admin-tab.active{
    background:#6b3515;
    color:white;
}
.order-admin{
    border:1px solid #d5b58f;
    background:#fffaf1;
    border-radius:18px;
    padding:20px;
    margin-bottom:18px;
}
.action-btn{
    border:0;
    border-radius:10px;
    padding:10px 14px;
    margin:5px;
    font-weight:bold;
    cursor:pointer;
}
.start{background:#8b5a2b;color:white}
.cancel{background:#9b2f1f;color:white}
.send{background:#6b3515;color:white}
.done{background:#4f6f2f;color:white}
.badge-pending{background:#f8e3bd;color:#6b3515}
.badge-preparing{background:#ead0a8;color:#5b2e12}
.badge-out{background:#dfc29a;color:#4b2c17}
.badge-completed{background:#dbe8c7;color:#36551e}
.badge-cancelled{background:#f2c5bd;color:#7a1f14}

@media(max-width:900px){
    .grid,.popular-grid,.stats-grid{
        grid-template-columns:repeat(2,1fr);
    }
    .search-row{
        flex-wrap:wrap;
    }
}
@media(max-width:600px){
    .grid,.popular-grid,.stats-grid{
        grid-template-columns:1fr;
    }
    .top,.admin-header{
        flex-direction:column;
        align-items:flex-start;
        gap:15px;
    }
}
</style>
</head>

<body>

<?php if($isAdmin) { ?>

<div class="admin-body">
    <div class="admin-header">
        <div class="admin-title">
            <h1>👨‍💼 Admin Dashboard</h1>
            <p>Milk Tea Haven Orders Management</p>
        </div>
        <a href="?logout=1" class="btn">Logout</a>
    </div>

    <div class="stats-grid">
        <div class="stat pending"><p>⏳ Pending Orders</p><h2 id="pendingCount">0</h2></div>
        <div class="stat preparing"><p>👨‍🍳 Preparing</p><h2 id="preparingCount">0</h2></div>
        <div class="stat delivery"><p>🚚 Out for Delivery</p><h2 id="deliveryCount">0</h2></div>
        <div class="stat revenue"><p>💰 Total Revenue</p><h2>₱<span id="totalRevenue">0.00</span></h2></div>
    </div>

    <div class="admin-card">
        <h2>Recent Orders</h2>
        <div id="recentOrders"></div>
    </div>

    <br>

    <div class="admin-card">
        <h2>📦 Orders Management</h2>
        <div class="admin-tabs">
            <button class="admin-tab active" onclick="setAdminFilter('All',this)">All Orders</button>
            <button class="admin-tab" onclick="setAdminFilter('Pending',this)">Pending</button>
            <button class="admin-tab" onclick="setAdminFilter('Preparing',this)">Preparing</button>
            <button class="admin-tab" onclick="setAdminFilter('Out for Delivery',this)">Out for Delivery</button>
            <button class="admin-tab" onclick="setAdminFilter('Completed',this)">Completed</button>
        </div>
        <div id="adminOrders"></div>
    </div>
</div>

<div class="toast" id="toast"></div>

<script>
let adminFilter = "All";
let orders = JSON.parse(localStorage.getItem("milkTeaOrders") || "[]");

function saveOrders(){
    localStorage.setItem("milkTeaOrders", JSON.stringify(orders));
}

function showToast(msg){
    let toast = document.getElementById("toast");
    toast.innerText = msg;
    toast.style.display = "block";
    setTimeout(()=>toast.style.display="none", 2500);
}

function getBadgeClass(status){
    if(status=="Pending") return "badge-pending";
    if(status=="Preparing") return "badge-preparing";
    if(status=="Out for Delivery") return "badge-out";
    if(status=="Completed") return "badge-completed";
    if(status=="Cancelled") return "badge-cancelled";
    return "badge-pending";
}

function updateAdminStats(){
    document.getElementById("pendingCount").innerText = orders.filter(o=>o.status=="Pending").length;
    document.getElementById("preparingCount").innerText = orders.filter(o=>o.status=="Preparing").length;
    document.getElementById("deliveryCount").innerText = orders.filter(o=>o.status=="Out for Delivery").length;

    let revenue = orders
        .filter(o=>o.status!="Cancelled")
        .reduce((sum,o)=>sum + Number(o.total),0);

    document.getElementById("totalRevenue").innerText = revenue.toFixed(2);

    document.getElementById("recentOrders").innerHTML = orders.slice(-5).reverse().map((o)=>`
        <div class="order-admin">
            <b>Order #${o.id}</b>
            <span class="status-badge ${getBadgeClass(o.status)}" style="float:right">${o.status}</span>
            <p>${o.fullname} - ₱${Number(o.total).toFixed(2)}</p>
        </div>
    `).join("") || "<p>No recent orders.</p>";
}

function setAdminFilter(status,btn){
    adminFilter = status;
    document.querySelectorAll(".admin-tab").forEach(b=>b.classList.remove("active"));
    btn.classList.add("active");
    renderAdminOrders();
}

function changeOrderStatus(id,status){
    let order = orders.find(o=>o.id==id);
    if(order){
        order.status = status;

        if(status=="Pending"){order.progress=25}
        if(status=="Preparing"){order.progress=50}
        if(status=="Out for Delivery"){order.progress=75}
        if(status=="Completed"){order.progress=100}
        if(status=="Cancelled"){order.progress=0}

        saveOrders();
        showToast("Order #" + id + " updated to " + status);
        renderAdminOrders();
        updateAdminStats();
    }
}

function renderAdminOrders(){
    let list = orders.slice().reverse();

    if(adminFilter!="All"){
        list = list.filter(o=>o.status==adminFilter);
    }

    document.getElementById("adminOrders").innerHTML = list.length ? list.map(o=>`
        <div class="order-admin">
            <h3>Order #${o.id} <span class="status-badge ${getBadgeClass(o.status)}">${o.status}</span></h3>
            <small>${o.date}</small>
            <p><b>Customer:</b> ${o.fullname}</p>
            <p><b>Phone:</b> ${o.phone}</p>
            <p><b>Address:</b> ${o.address}</p>
            <p><b>Payment:</b> ${o.payment}</p>

            <hr>
            ${o.items.map(item=>`
                <p>
                    ${item.qty}x ${item.name}
                    ${item.sugar ? `<br><small>Sugar: ${item.sugar} | Ice: ${item.ice} | Size: ${item.size}</small>` : ``}
                    ${item.toppings && item.toppings.length ? `<br><small>Toppings: ${item.toppings.join(", ")}</small>` : ``}
                    <span style="float:right">₱${(item.price * item.qty).toFixed(2)}</span>
                </p>
            `).join("")}

            <hr>
            <h3>Total: ₱${Number(o.total).toFixed(2)}</h3>

            <div class="progress"><div class="progress-bar" style="width:${o.progress}%"></div></div>
            <br>

            ${o.status=="Pending" ? `
                <button class="action-btn start" onclick="changeOrderStatus(${o.id},'Preparing')">✅ Start Preparing</button>
                <button class="action-btn cancel" onclick="changeOrderStatus(${o.id},'Cancelled')">❌ Cancel Order</button>
            ` : ""}

            ${o.status=="Preparing" ? `
                <button class="action-btn send" onclick="changeOrderStatus(${o.id},'Out for Delivery')">🚚 Send for Delivery</button>
            ` : ""}

            ${o.status=="Out for Delivery" ? `
                <button class="action-btn done" onclick="changeOrderStatus(${o.id},'Completed')">✅ Mark as Delivered</button>
            ` : ""}
        </div>
    `).join("") : "<p>No orders found.</p>";
}

updateAdminStats();
renderAdminOrders();
</script>

<?php } else { ?>

<div class="header">
    <div class="top">
        <div class="brand">
            <div style="font-size:40px">🧋</div>
            <div>
                <h1>Milk Tea Haven</h1>
                <span>Premium Drinks & Waffles</span>
            </div>
        </div>

        <div>
            <?php if($isLoggedIn){ ?>
                <button onclick="openHistory()" class="header-action">My Orders (<span id="orderCount">0</span>)</button>
                <button onclick="showFavorites()" class="header-action">❤️ Favorites (<span id="favCount">0</span>)</button>
                <button onclick="openCart()" class="btn">🛒 Cart <span id="cartCount"></span></button>
                <span class="user-badge">👤 <?php echo $_SESSION['user']; ?></span>
                <a href="?logout=1" class="logout-link">↪</a>
            <?php } else { ?>
                <button onclick="openAuth('login')" class="btn">Login / Sign Up</button>
            <?php } ?>
        </div>
    </div>

    <div class="search-row">
        <input class="search" id="search" onkeyup="renderProducts()" placeholder="🔍 Search products...">
        <button class="cat-btn active" onclick="setCat('All',this)">All</button>
        <button class="cat-btn" onclick="setCat('Milk Tea',this)">Milk Tea</button>
        <button class="cat-btn" onclick="setCat('Waffles',this)">Waffles</button>
        <button class="cat-btn" onclick="setCat('Add-ons',this)">Add-ons</button>
    </div>
</div>

<div class="container">
    <div class="promo-banner">
        🎁 Promo Codes: WELCOME10 = 10% OFF | SAVE20 = 20% OFF | FREESHIP = 15% OFF | FIRSTORDER = 25% OFF
    </div>

    <div class="popular" id="popular">
        <h2>⭐ Popular Items</h2>
        <div class="popular-grid">
            <div class="popular-item">🧋<br>Classic Milk Tea<br>₱99</div>
            <div class="popular-item">🧋<br>Taro Milk Tea<br>₱109</div>
            <div class="popular-item">🧋<br>Brown Sugar Milk Tea<br>₱109</div>
            <div class="popular-item">🧇<br>Classic Waffle<br>₱129</div>
        </div>
    </div>

    <div class="title-row">
        <h1 id="pageTitle">All Products</h1>
        <p><span id="itemCount"></span> items</p>
    </div>

    <div class="grid" id="products"></div>
</div>

<div class="modal" id="authModal">
    <div class="modal-box">
        <span class="close" onclick="closeModal('authModal')">×</span>
        <div style="text-align:center">
            <div class="logo" style="width:75px;height:75px;font-size:38px;margin-bottom:15px">🧋</div>
            <h1 style="color:#5b2e12;margin:0">Milk Tea Haven</h1>
            <p>Login muna bago mag-add to cart / checkout.</p>
            <div class="admin-hint">Admin Login: username <b>admin</b></div>
            <?php if(isset($error)){ ?>
                <p style="color:red;font-weight:bold;"><?php echo $error; ?></p>
            <?php } ?>
        </div>

        <form method="POST" id="loginForm">
            <h2 style="color:#5b2e12">Login</h2>
            <label><b>Username</b></label>
            <input type="text" name="username" placeholder="Enter username" required>
            <label><b>Password</b></label>
            <input type="password" name="password" placeholder="Enter password">
            <button class="btn" name="login" style="width:100%">Login</button>
            <p style="text-align:center">Wala pang account? <a href="javascript:void(0)" onclick="openAuth('register')" class="orange"><b>Sign Up</b></a></p>
        </form>

        <form method="POST" id="registerForm" style="display:none">
            <h2 style="color:#5b2e12">Sign Up</h2>
            <label><b>Username</b></label>
            <input type="text" name="username" placeholder="Choose username" required>
            <label><b>Password</b></label>
            <input type="password" name="password" placeholder="Choose password" required>
            <button class="btn" name="register" style="width:100%">Create Account</button>
            <p style="text-align:center">May account na? <a href="javascript:void(0)" onclick="openAuth('login')" class="orange"><b>Login</b></a></p>
        </form>
    </div>
</div>

<div class="modal" id="customizeModal">
    <div class="modal-box custom-scroll" style="padding:0">
        <div style="background:linear-gradient(90deg,#6b3515,#b87538,#e3b06b);color:white;padding:22px">
            <span class="close" onclick="closeModal('customizeModal')" style="color:white">×</span>
            <h2>Customize Your Drink</h2>
        </div>

        <div style="padding:25px">
            <h2 id="customName"></h2>
            <h3 style="color:#7a3f18">₱<span id="customPrice"></span></h3>

            <h3>Sugar Level</h3>
            <div class="option-grid">
                <button class="option-btn sugar" onclick="selectSugar(this,'0%')">0%</button>
                <button class="option-btn sugar active" onclick="selectSugar(this,'25%')">25%</button>
                <button class="option-btn sugar" onclick="selectSugar(this,'50%')">50%</button>
                <button class="option-btn sugar" onclick="selectSugar(this,'100%')">100%</button>
            </div>

            <h3>Ice Level</h3>
            <div class="option-grid">
                <button class="option-btn ice" onclick="selectIce(this,'0%')">0%</button>
                <button class="option-btn ice" onclick="selectIce(this,'25%')">25%</button>
                <button class="option-btn ice active" onclick="selectIce(this,'50%')">50%</button>
                <button class="option-btn ice" onclick="selectIce(this,'100%')">100%</button>
            </div>

            <h3>Size</h3>
            <div class="option-grid">
                <button class="option-btn size active" onclick="selectSize(this,'Regular',0)">Regular</button>
                <button class="option-btn size" onclick="selectSize(this,'Large',20)">Large +₱20</button>
            </div>

            <h3>Add Toppings</h3>
            <div class="topping-grid">
                <button class="topping-btn" onclick="toggleTopping(this,'Boba')">Boba +₱10</button>
                <button class="topping-btn" onclick="toggleTopping(this,'Pudding')">Pudding +₱10</button>
                <button class="topping-btn" onclick="toggleTopping(this,'Aloe Vera')">Aloe Vera +₱10</button>
                <button class="topping-btn" onclick="toggleTopping(this,'Jelly')">Jelly +₱10</button>
                <button class="topping-btn" onclick="toggleTopping(this,'Cheese Foam')">Cheese Foam +₱10</button>
                <button class="topping-btn" onclick="toggleTopping(this,'Oreo')">Oreo +₱10</button>
            </div>

            <h3>Special Instructions</h3>
            <textarea id="specialInstructions" placeholder="Example: less sweet, extra cold, no pearls..."></textarea>

            <div class="custom-total">
                <span>Total:</span>
                <b style="color:#7a3f18">₱<span id="customLiveTotal">0.00</span></b>
            </div>
            <br>

            <button class="btn" onclick="addCustomizedDrink()" style="width:100%">Add to Cart</button>
        </div>
    </div>
</div>

<div class="modal" id="cartModal">
    <div class="modal-box">
        <span class="close" onclick="closeModal('cartModal')">×</span>
        <h2 style="color:#5b2e12">Your Cart</h2>

        <div id="cartItems"></div>

        <label><b>Promo Code</b></label>
        <input id="cartPromo" placeholder="WELCOME10, SAVE20, FREESHIP, FIRSTORDER">
        <button class="btn" onclick="applyPromo()" style="width:100%;margin-bottom:15px">Apply Promo</button>
        <p id="promoDisplay" class="orange"></p>

        <hr>
        <p>Subtotal: <span style="float:right">₱<span id="subtotalAmount">0.00</span></span></p>
        <p>Discount: <span style="float:right">₱<span id="discountAmount">0.00</span></span></p>
        <p>Tax (8%): <span style="float:right">₱<span id="taxAmount">0.00</span></span></p>
        <hr>

        <h2>Total: <span style="float:right;color:#7a3f18">₱<span id="totalAmount">0.00</span></span></h2>

        <button class="btn" onclick="openCheckout()" style="width:100%;margin-top:15px">Proceed to Checkout</button>
    </div>
</div>

<div class="modal" id="checkoutModal">
    <div class="modal-box">
        <span class="close" onclick="closeModal('checkoutModal')">×</span>
        <h1 style="color:#5b2e12">Checkout</h1>

        <label><b>Full Name</b></label>
        <input id="fullname" placeholder="Juan Dela Cruz">

        <label><b>Phone Number</b></label>
        <input id="phone" placeholder="09xxxxxxxxx">

        <label><b>Delivery Address</b></label>
        <textarea id="address" placeholder="House no., Street, City"></textarea>

        <label><b>Schedule Delivery</b></label>
        <input type="datetime-local" id="deliveryTime">

        <label><b>Payment Method</b></label>
        <select id="payment">
            <option>Cash on Delivery</option>
            <option>Credit Card</option>
            <option>GCash</option>
            <option>PayMaya</option>
        </select>

        <div class="total">
            <span>Total Amount:</span>
            <b class="orange">₱<span id="checkoutTotal">0.00</span></b>
        </div>
        <br>

        <button class="btn gray" onclick="closeModal('checkoutModal')" style="width:48%">Cancel</button>
        <button class="btn" onclick="placeOrder()" style="width:48%">Place Order</button>
    </div>
</div>

<div class="modal" id="historyModal">
    <div class="modal-box" style="width:650px">
        <span class="close" onclick="closeModal('historyModal')">×</span>
        <h1 style="color:#5b2e12">Order History</h1>
        <div id="historyItems"></div>
    </div>
</div>

<div class="toast" id="toast"></div>

<script>
let category = "All";
let cart = [];
let orders = JSON.parse(localStorage.getItem("milkTeaOrders") || "[]");
let favorites = [];
let selectedProduct = null;
let selectedSugar = "25%";
let selectedIce = "50%";
let selectedSize = "Regular";
let sizeAdd = 0;
let selectedToppings = [];
let appliedPromo = "";
let discountRate = 0;

const isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
const currentUser = <?php echo json_encode($_SESSION['user'] ?? "guest"); ?>;

const promoCodes = {
    "WELCOME10": 0.10,
    "SAVE20": 0.20,
    "FREESHIP": 0.15,
    "FIRSTORDER": 0.25
};

function openAuth(mode = "login"){
    document.getElementById("authModal").style.display = "flex";
    document.getElementById("loginForm").style.display = mode === "login" ? "block" : "none";
    document.getElementById("registerForm").style.display = mode === "register" ? "block" : "none";
}

function requireLogin(){
    if(!isLoggedIn){
        openAuth("login");
        showToast("Please login first before adding to cart.");
        return false;
    }
    return true;
}

const products = [
 {name:"Classic Milk Tea", desc:"Traditional black tea with creamy milk", price:99, cat:"Milk Tea", emoji:"🧋", popular:true, rating:4.8, reviews:120},
 {name:"Taro Milk Tea", desc:"Sweet taro flavor with pearls", price:109, cat:"Milk Tea", emoji:"🧋", popular:true, rating:4.7, reviews:98},
 {name:"Matcha Milk Tea", desc:"Premium matcha with fresh milk", price:119, cat:"Milk Tea", emoji:"🍵", rating:4.6, reviews:75},
 {name:"Brown Sugar Milk Tea", desc:"Rich brown sugar with boba pearls", price:109, cat:"Milk Tea", emoji:"🧋", popular:true, rating:4.9, reviews:150},
 {name:"Thai Milk Tea", desc:"Authentic Thai tea with condensed milk", price:115, cat:"Milk Tea", emoji:"🧋", rating:4.5, reviews:60},
 {name:"Wintermelon Milk Tea", desc:"Refreshing wintermelon with fresh milk", price:105, cat:"Milk Tea", emoji:"🧋", rating:4.4, reviews:52},

 {name:"Classic Waffle", desc:"Golden Belgian waffle with butter & syrup", price:129, cat:"Waffles", emoji:"🧇", popular:true, rating:4.8, reviews:85},
 {name:"Chocolate Waffle", desc:"Waffle topped with chocolate sauce & cream", price:149, cat:"Waffles", emoji:"🧇", rating:4.6, reviews:70},
 {name:"Strawberry Waffle", desc:"Fresh strawberries with whipped cream", price:159, cat:"Waffles", emoji:"🧇", rating:4.7, reviews:66},
 {name:"Nutella Banana Waffle", desc:"Nutella spread with sliced bananas", price:169, cat:"Waffles", emoji:"🧇", popular:true, rating:4.9, reviews:92},
 {name:"Blueberry Waffle", desc:"Fresh blueberries with maple syrup", price:149, cat:"Waffles", emoji:"🧇", rating:4.5, reviews:55},
 {name:"Matcha Waffle", desc:"Matcha-infused waffle with red bean", price:159, cat:"Waffles", emoji:"🧇", rating:4.4, reviews:47},

 {name:"Extra Boba Pearls", desc:"Add extra tapioca pearls to any drink", price:20, cat:"Add-ons", emoji:"⚫", rating:4.8, reviews:40},
 {name:"Whipped Cream", desc:"Fresh whipped cream topping", price:25, cat:"Add-ons", emoji:"🥛", rating:4.5, reviews:35},
 {name:"Ice Cream Scoop", desc:"Vanilla ice cream scoop", price:35, cat:"Add-ons", emoji:"🍨", rating:4.9, reviews:45}
];

function saveOrders(){
    localStorage.setItem("milkTeaOrders", JSON.stringify(orders));
}

function showToast(msg){
    let toast = document.getElementById("toast");
    toast.innerText = msg;
    toast.style.display = "block";
    setTimeout(()=>toast.style.display="none", 2500);
}

function setCat(cat,btn){
    category = cat;
    document.querySelectorAll('.cat-btn').forEach(b=>b.classList.remove('active'));
    btn.classList.add('active');
    renderProducts();
}

function showFavorites(){
    category = "Favorites";
    document.querySelectorAll('.cat-btn').forEach(b=>b.classList.remove('active'));
    renderProducts();
}

function toggleFavorite(name){
    if(!requireLogin()) return;

    if(favorites.includes(name)){
        favorites = favorites.filter(f=>f!==name);
        showToast("Removed from favorites");
    }else{
        favorites.push(name);
        showToast("Added to favorites ❤️");
    }

    if(document.getElementById("favCount")){
        document.getElementById("favCount").innerText = favorites.length;
    }

    renderProducts();
}

function renderProducts(){
    let search = document.getElementById('search').value.toLowerCase();

    let list = products.filter(p =>
        (category=="All" || p.cat==category || (category=="Favorites" && favorites.includes(p.name))) &&
        p.name.toLowerCase().includes(search)
    );

    document.getElementById('popular').style.display = category=="All" ? "block" : "none";
    document.getElementById('pageTitle').innerText =
        category=="All" ? "All Products" :
        category=="Milk Tea" ? "Milk Tea Collection" :
        category=="Waffles" ? "Delicious Waffles" :
        category=="Favorites" ? "My Favorites" : "Add-ons";

    document.getElementById('itemCount').innerText = list.length;

    document.getElementById('products').innerHTML = list.map((p)=>`
        <div class="card product">
            <button class="fav-btn ${favorites.includes(p.name) ? 'active' : ''}" onclick="toggleFavorite('${p.name}')">♥</button>
            ${p.popular ? '<span class="badge">POPULAR</span>' : ''}
            <div class="emoji">${p.emoji}</div>
            <h2>${p.name}</h2>
            <div class="rating">⭐ ${p.rating} <small>(${p.reviews} reviews)</small></div>
            <p>${p.desc}</p>
            <br>
            <span class="price">₱${p.price.toFixed(2)}</span>
            <button class="btn add" onclick="addToCart('${p.name}',${p.price})">
                ${p.cat === "Milk Tea" ? "Customize" : "Add"}
            </button>
        </div>
    `).join('');
}

function updateCustomTotal(){
    if(!selectedProduct) return;
    let total = selectedProduct.price + sizeAdd + (selectedToppings.length * 10);
    document.getElementById("customPrice").innerText = total.toFixed(2);
    document.getElementById("customLiveTotal").innerText = total.toFixed(2);
}

function addToCart(name,price){
    if(!requireLogin()) return;
    let product = products.find(p => p.name === name);

    if(product.cat === "Milk Tea"){
        selectedProduct = product;
        selectedSugar = "25%";
        selectedIce = "50%";
        selectedSize = "Regular";
        sizeAdd = 0;
        selectedToppings = [];

        document.getElementById("customName").innerText = product.name;
        document.getElementById("customPrice").innerText = product.price.toFixed(2);
        document.getElementById("specialInstructions").value = "";

        document.querySelectorAll(".sugar,.ice,.size,.topping-btn").forEach(b=>b.classList.remove("active"));
        document.querySelectorAll(".sugar")[1].classList.add("active");
        document.querySelectorAll(".ice")[2].classList.add("active");
        document.querySelectorAll(".size")[0].classList.add("active");

        document.getElementById("customizeModal").style.display = "flex";
        setTimeout(updateCustomTotal, 100);
        return;
    }

    addFinalToCart(product, "", "", "Regular", [], "");
}

function selectSugar(btn,level){
    selectedSugar = level;
    document.querySelectorAll(".sugar").forEach(b=>b.classList.remove("active"));
    btn.classList.add("active");
}

function selectIce(btn,level){
    selectedIce = level;
    document.querySelectorAll(".ice").forEach(b=>b.classList.remove("active"));
    btn.classList.add("active");
}

function selectSize(btn,size,add){
    selectedSize = size;
    sizeAdd = add;
    document.querySelectorAll(".size").forEach(b=>b.classList.remove("active"));
    btn.classList.add("active");
    updateCustomTotal();
}

function toggleTopping(btn,topping){
    if(selectedToppings.includes(topping)){
        selectedToppings = selectedToppings.filter(t=>t!==topping);
        btn.classList.remove("active");
    }else{
        selectedToppings.push(topping);
        btn.classList.add("active");
    }
    updateCustomTotal();
}

function addCustomizedDrink(){
    let instructions = document.getElementById("specialInstructions").value;
    addFinalToCart(selectedProduct, selectedSugar, selectedIce, selectedSize, selectedToppings, instructions);
    closeModal("customizeModal");
}

function addFinalToCart(product,sugar,ice,size,toppings,instructions){
    let finalPrice = product.price + (size=="Large" ? 20 : 0) + (toppings.length * 10);

    cart.push({
        name:product.name,
        price:finalPrice,
        basePrice:product.price,
        qty:1,
        emoji:product.emoji,
        sugar:sugar,
        ice:ice,
        size:size,
        toppings:[...toppings],
        instructions:instructions
    });

    updateCart();
    showToast(product.name + " added to cart!");
}

function updateCart(){
    let count = cart.reduce((sum,item)=>sum + item.qty,0);
    if(document.getElementById('cartCount')){
        document.getElementById('cartCount').innerText = count ? `(${count})` : "";
    }
}

function increaseQty(index){
    cart[index].qty++;
    openCart();
    updateCart();
}

function decreaseQty(index){
    cart[index].qty--;
    if(cart[index].qty <= 0){
        cart.splice(index,1);
    }
    openCart();
    updateCart();
}

function getTotals(){
    let subtotal = cart.reduce((sum,item)=>sum + (item.price * item.qty),0);
    let discount = subtotal * discountRate;
    let tax = (subtotal - discount) * 0.08;
    let total = subtotal - discount + tax;
    return {subtotal,discount,tax,total};
}

function applyPromo(){
    let code = document.getElementById("cartPromo").value.toUpperCase();
    if(promoCodes[code]){
        appliedPromo = code;
        discountRate = promoCodes[code];
        showToast("Promo applied: " + code);
    }else{
        appliedPromo = "";
        discountRate = 0;
        showToast("Invalid promo code");
    }
    openCart();
}

function openCart(){
    if(!requireLogin()) return;
    let totals = getTotals();

    document.getElementById('cartItems').innerHTML = cart.length ? cart.map((p,i)=>`
        <div class="cart-product">
            <div class="cart-emoji">${p.emoji}</div>
            <div>
                <h3 style="margin:0">${p.name}</h3>
                ${p.sugar ? `<small>Sugar: ${p.sugar} | Ice: ${p.ice} | Size: ${p.size}</small><br>` : ``}
                ${p.toppings && p.toppings.length ? `<small>Toppings: ${p.toppings.join(", ")}</small><br>` : ``}
                ${p.instructions ? `<small>Note: ${p.instructions}</small><br>` : ``}
                <p class="small-price" style="margin:5px 0">₱${p.price.toFixed(2)}</p>
                <button class="qty-btn" onclick="decreaseQty(${i})">−</button>
                <span class="qty-num">${p.qty}</span>
                <button class="qty-btn" onclick="increaseQty(${i})">+</button>
            </div>
            <div class="small-price">₱${(p.price * p.qty).toFixed(2)}</div>
        </div>
    `).join('') : "<p>Your cart is empty.</p>";

    document.getElementById('subtotalAmount').innerText = totals.subtotal.toFixed(2);
    document.getElementById('discountAmount').innerText = totals.discount.toFixed(2);
    document.getElementById('taxAmount').innerText = totals.tax.toFixed(2);
    document.getElementById('totalAmount').innerText = totals.total.toFixed(2);
    document.getElementById('promoDisplay').innerText = appliedPromo ? "Applied: " + appliedPromo : "";

    document.getElementById('cartModal').style.display="flex";
}

function openCheckout(){
    if(cart.length==0){
        alert("Cart is empty");
        return;
    }

    closeModal('cartModal');
    let totals = getTotals();
    document.getElementById('checkoutTotal').innerText = totals.total.toFixed(2);
    document.getElementById('checkoutModal').style.display="flex";
}

function placeOrder(){
    let fullname = document.getElementById('fullname').value;
    let phone = document.getElementById('phone').value;
    let address = document.getElementById('address').value;
    let payment = document.getElementById('payment').value;
    let deliveryTime = document.getElementById('deliveryTime').value;

    if(fullname=="" || phone=="" || address==""){
        alert("Please complete checkout form.");
        return;
    }

    let totals = getTotals();
    let order = {
        id: Date.now(),
        user: currentUser,
        items:[...cart],
        subtotal:totals.subtotal,
        tax:totals.tax,
        discount:totals.discount,
        total:totals.total,
        date:new Date().toLocaleString(),
        fullname:fullname,
        phone:phone,
        address:address,
        payment:payment,
        deliveryTime:deliveryTime,
        status:"Pending",
        progress:25,
        eta:"30-45 minutes"
    };

    orders.push(order);
    saveOrders();

    let orderData = {
        fullname: fullname,
        phone: phone,
        address: address,
        payment: payment,
        total: totals.total,
        items: cart
    };

    fetch("save_order.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(orderData)
    }).catch(()=>{});

    cart=[];
    appliedPromo="";
    discountRate=0;
    updateCart();

    if(document.getElementById('orderCount')){
        document.getElementById('orderCount').innerText = orders.filter(o=>o.user==currentUser).length;
    }

    closeModal('checkoutModal');
    showToast("Order placed successfully!");
}

function openHistory(){
    orders = JSON.parse(localStorage.getItem("milkTeaOrders") || "[]");
    let myOrders = orders.filter(o=>o.user==currentUser);

    document.getElementById('orderCount').innerText = myOrders.length;

    document.getElementById('historyItems').innerHTML = myOrders.length ? myOrders.slice().reverse().map((o)=>`
        <div class="card" style="margin-bottom:15px">
            <p>
                Order #${o.id}
                <span class="status-badge ${o.status=='Completed'?'badge-completed':o.status=='Preparing'?'badge-preparing':o.status=='Out for Delivery'?'badge-out':'badge-pending'}">📦 ${o.status}</span>
            </p>
            <small>${o.date}</small>
            <p style="color:#7a3f18">⏱ ${o.eta}</p>

            <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:5px">
                <span>Pending</span>
                <span>Preparing</span>
                <span>Delivery</span>
                <span>Done</span>
            </div>
            <div class="progress"><div class="progress-bar" style="width:${o.progress}%"></div></div>
            <br>

            ${o.items.map(x=>`
                <p>${x.qty}x ${x.name}
                ${x.sugar ? `<br><small>Sugar: ${x.sugar} | Ice: ${x.ice} | Size: ${x.size}</small>` : ``}
                <span style="float:right">₱${(x.price * x.qty).toFixed(2)}</span></p>
            `).join('')}

            <hr>
            <h3>Total: <span style="float:right;color:#7a3f18">₱${Number(o.total).toFixed(2)}</span></h3>
        </div>
    `).join('') : "<p>No orders yet.</p>";

    document.getElementById('historyModal').style.display="flex";
}

function closeModal(id){
    document.getElementById(id).style.display="none";
}

if(document.getElementById('orderCount')){
    document.getElementById('orderCount').innerText = orders.filter(o=>o.user==currentUser).length;
}

renderProducts();

<?php if(isset($error)){ ?>
openAuth('login');
<?php } ?>
</script>

<?php } ?>

</body>
</html>