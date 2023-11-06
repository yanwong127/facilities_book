<?php

include_once('db.php');
include_once('header.php');

$item = "SELECT * FROM `item`";
$qry = mysqli_query($conn, $item);


if (isset($_POST["book"])) {
    
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<style>
    .custom-table {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-gap: 16px;
    }

    .custom-table .td {
        display: grid;
        grid-template-columns: 1fr;
        grid-template-rows: auto auto;
        text-align: center;
    }

    .item-row {
        align-items: center;
        justify-content: center;
    }

    .item-container img {
        max-width: 300px;
        max-height: 300px;
    }

    .item-name {
        font-weight: bold;
    }

    #clickable-div:hover {
        cursor: pointer;
    }

    a {
        text-decoration-line: none;
        color: black;
    }

    .rounded-image {
        border-radius: 10px;
    }

    #dialog-image {
        width: 400px;
        height: 200px;
    }
</style>

<body>

    <div class="custom-table" id="clickable-div">
        <?php while ($row = mysqli_fetch_array($qry)) { ?>
            <div class="td">
                <div class="item-row">
                    <div class="item-container" id="none">
                        <a href="itemDetail.php?id=<?= $row['item_id'] ?>">
                            <img class="rounded-image" src="img/<?= $row['item_img'] ?>">
                            <div class="item-name">
                                <?= $row['item_name'] ?>
                            </div>
                            <div class="item-quantity" style="display: none;">
                                <?= $row['quantity'] ?>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        <?php } ?>
    </div>


    <dialog>
        <button autofocus>Close</button>
        <h2 id="dialog-title"></h2>
        <h2 id="dialog-item-name" style="text-align: center;"></h2>
        <img id="dialog-image" src="" alt="Item Image">
        <p id="dialog-description"></p>
        <p id="dialog-quantity"></p>
        <button>Book</button>
    </dialog>



</body>

</html>


<script>
    const dialog = document.querySelector("dialog");
    const showButton = document.querySelector("dialog + button");
    const closeButton = document.querySelector("dialog button");
    const dialogTitle = document.getElementById("dialog-title");
    const dialogImage = document.getElementById("dialog-image");
    const dialogDescription = document.getElementById("dialog-description");
    const dialogQuantity = document.getElementById("dialog-quantity");

    document.querySelectorAll(".item-container a").forEach((link) => {
        link.addEventListener("click", (e) => {
            e.preventDefault();

            const itemTitle = link.querySelector(".item-name").textContent;
            const itemImageSrc = link.querySelector("img").src;
            const itemDescription = "Description of the item";
            const itemQuantity = link.querySelector(".item-quantity").textContent;

            dialogTitle.textContent = itemTitle;
            dialogImage.src = itemImageSrc;
            dialogDescription.textContent = itemDescription;
            dialogQuantity.textContent = "Quantity: " + itemQuantity;

            dialog.showModal();
        });
    });

    closeButton.addEventListener("click", () => {
        dialog.close();
    });

    // dialog.addEventListener("click", (e) => {
    //     if (e.target === dialog) {
    //         dialog.close();
    //     }
    // });

    dialog.querySelector("*").addEventListener("click", (e) => {
        e.stopPropagation();
    });
</script>