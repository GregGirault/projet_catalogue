<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="galerie.css">
    <title>Galerie</title>
</head>

<body>
    
    <section class="gallery">
        <div class="container">
            <div class="rowi">
                <div class="gallery-filter">
                    <span class="filter-item active" data-filter="all">All</span>
                    <span class="filter-item" data-filter="parfum">Parfum</span>
                    <span class="filter-item" data-filter="Déodorant">Déodorant</span>
                    <span class="filter-item" data-filter="Shampoing">Shampoing</span>
                </div>
            </div>
            <div class="row">
                <!-- Galerie début -->
                <div class="gallery-item parfum">
                    <div class="gallery-item-inner">
                        <img src="10.png" alt="parfum">
                    </div>
                </div>

                <div class="gallery-item déodorant">
                    <div class="gallery-item-inner">
                        <img src="14.png" alt="déodorant">
                    </div>
                </div>

                <div class="gallery-item déodorant">
                    <div class="gallery-item-inner">
                        <img src="13.png" alt="déodorant">
                    </div>
                </div>

                <div class="gallery-item parfum">
                    <div class="gallery-item-inner">
                        <img src="11.png" alt="parfum">
                    </div>
                </div>

                <div class="gallery-item parfum">
                    <div class="gallery-item-inner">
                        <img src="12.png" alt="parfum">
                    </div>
                </div>

                <div class="gallery-item shampoing">
                    <div class="gallery-item-inner">
                        <img src="15.png" alt="Shampoing">
                    </div>
                </div>
                <!-- Galerie fin -->
            </div>
        </div>
    </section>
    <script src="galerie.js"></script>
</body>

</html>
