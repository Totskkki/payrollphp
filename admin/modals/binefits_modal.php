<!DOCTYPE html>
<html lang="en>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0>
    <title>Benefits Modal</title>
</head>

<body>

<div id="benefitsModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h2>Employee Benefits</h2>
        <p>Details about benefits...</p>
        <!--Add more content as needed -->
        <button onclick="openModal()"View Benefits</button>
    </div>
</div>


</body>
<body>
<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100;
        height: 100;
        overflow: auto;
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0,0.4);
    }

    .modALcontent {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    .close-button {
       color: #aaa;
       float: right;
       font-size: 28px;
       font-weight:  bold;
    }

    .close-button:hover,
    .close-button:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>
</body>

    <body>
        <script>
            const modal= document.getElementById("benefitsModal");
            const closeButton= document.querySelector(".close-buton");

            function openModal() {
                modal.style.display = "block";
            }

            function closeModal() {
                modal.style.display = "none";
            }

            closeButton.addEventListener("click", closeModal);
            window.addEventListener("click", (event) => {
                if ( event.target === modal) {
                    closeModal();
                }
            })
        </script>
    </body>