<?php require_once 'header.php'; ?>

</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12 mt-4">
                <div class="jumbotron text-center mt-4">
                    <h1 class="display-3">Thank You!</h1>
                    <p class="lead"><strong>Order Placed Successfully</strong> Your order id is <span id="order_id"></span> and store order is <span id="store_orderno"></span> .</p>
                    <hr>
                    <p class="lead">
                        <a class="btn btn-primary btn-sm" href="index.php" role="button">Continue to homepage</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    let order_id = sessionStorage.getItem("order_id");
    document.getElementById('order_id').innerHTML = "#"+order_id;
    let store_orderno = sessionStorage.getItem("store_orderno");
    document.getElementById('store_orderno').innerHTML = "#"+store_orderno;
</script>

<?php require_once 'footer.php'; ?>