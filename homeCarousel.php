<!-- carousel.php -->
<div id="bookCarousel" class="carousel slide" data-ride="carousel" data-interval="3000">
    <ol class="carousel-indicators">
        <li data-target="#bookCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#bookCarousel" data-slide-to="1"></li>
        <li data-target="#bookCarousel" data-slide-to="2"></li>
        <li data-target="#bookCarousel" data-slide-to="3"></li>
    </ol>

    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="./img/slide1.jpg" class="d-block w-100 fixed-size" alt="Book 1">
            <div class="carousel-caption d-none d-md-block">
                <!-- <h5>Book Title 1</h5> -->
                <!-- <p>Description of Book 1.</p> -->
            </div>
        </div>
        <div class="carousel-item">
            <img src="./img/slide2.jpg" class="d-block w-100 fixed-size" alt="Book 2">
            <div class="carousel-caption d-none d-md-block">
                <!-- <h5>Book Title 2</h5> -->
                <!-- <p>Description of Book 2.</p> -->
            </div>
        </div>
        <div class="carousel-item">
            <img src="./img/slide3.jpg" class="d-block w-100 fixed-size" alt="Book 3">
            <div class="carousel-caption d-none d-md-block">
                <!-- <h5>Book Title 3</h5> -->
                <!-- <p>Description of Book 3.</p> -->
            </div>
        </div>
        <div class="carousel-item">
            <img src="./img/slide4.jpg" class="d-block w-100 fixed-size" alt="Book 4">
            <div class="carousel-caption d-none d-md-block">
                <!-- <h5>Book Title 4</h5> -->
                <!-- <p>Description of Book 4.</p> -->
            </div>
        </div>
    </div>

    <a class="carousel-control-prev" href="#bookCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#bookCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

<style>
    .fixed-size {
        width: 100%; /* Set desired width */
        height: 300px; /* Set desired height */
        object-fit: cover; /* Ensure images cover the area without distortion */

    }
</style>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
