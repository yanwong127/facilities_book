<?php
include_once('db.php');
include_once('header.php');

$place = "SELECT * FROM `place`";
$qry = mysqli_query($conn, $place);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        .place-row {
            align-items: center;
            justify-content: center;
        }

        .place-container img {
            max-width: 300px;
            max-height: 300px;
        }

        .place-name {
            font-weight: bold;
        }

        #clickable-div:hover {
            cursor: pointer;
        }

        a {
            text-decoration: none;
            color: black;
        }

        .rounded-image {
            border-radius: 10px;
        }

        #place-dialog-image {
            width: 400px;
            height: 200px;
        }
    </style>
</head>

<body>
    <div class="custom-table" id="clickable-div">
        <?php while ($row = mysqli_fetch_array($qry)) { ?>
            <div class="td">
                <div class="place-row">
                    <div class="place-container" id="none">
                        <a href="placeDetail.php?id=<?= $row['place_id'] ?>">
                            <img class="rounded-image" src="img/<?= $row['place_img'] ?>">
                            <div class="place-name">
                                <?= $row['placename'] ?>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <dialog id="place-dialog">
        <button autofocus>Close</button>
        <h2 id="place-dialog-title"></h2>
        <img id="place-dialog-image" src="" alt="Place Image">
        <p id="place-dialog-description"></p>
    </dialog>

    <script>
        const placeDialog = document.querySelector("#place-dialog");
        const placeDialogTitle = document.getElementById("place-dialog-title");
        const placeDialogImage = document.getElementById("place-dialog-image");
        const placeDialogDescription = document.getElementById("place-dialog-description");

        document.querySelectorAll(".place-container a").forEach((link) => {
            link.addEventListener("click", (e) => {
                e.preventDefault();

                const placeTitle = link.querySelector(".place-name").textContent;
                const placeImageSrc = link.querySelector("img").src;
                const placeDescription = "Description of the place";

                placeDialogTitle.textContent = placeTitle;
                placeDialogImage.src = placeImageSrc;
                placeDialogDescription.textContent = placeDescription;

                placeDialog.showModal();
            });
        });

        placeDialog.querySelector("button").addEventListener("click", () => {
            placeDialog.close();
        });
    </script>
</body>

</html>