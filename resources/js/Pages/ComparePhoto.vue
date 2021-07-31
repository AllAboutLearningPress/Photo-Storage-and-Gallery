<template>
    <div class="wrapper">
        <div class="content">
            <h2 class="fw-light mb-5">Compare duplicate</h2>

            <div class="img-comp-container">
                <div class="img-comp-img">
                    <img
                        src="https://img-comparison-slider.sneas.io/demo/images/before.webp"
                        width="300"
                        height="200"
                    />
                </div>
                <div ref="rightImage" class="img-comp-img img-comp-overlay">
                    <img
                        src="https://img-comparison-slider.sneas.io/demo/images/after.webp"
                        width="300"
                        height="200"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
<style >
.img-comp-container {
    position: relative;
    height: 200px; /*should be the same height as the images*/
}

.img-comp-img {
    position: absolute;
    width: auto;
    height: auto;
    overflow: hidden;
}

.img-comp-img img {
    display: block;
    /* vertical-align: middle; */
}

.img-comp-slider {
    position: absolute;
    z-index: 9;
    cursor: ew-resize;
    /*set the appearance of the slider:*/
    width: 40px;
    height: 40px;
    background-color: #2196f3;
    opacity: 0.7;
    border-radius: 50%;
}
</style>
<script>
import MainLayout from "../Layouts/MainLayout.vue";

//import { ImgComparisonSlider } from "@img-comparison-slider/vue";

export default {
    data() {
        return {
            data: {
                clicked: 0,
                slider: null,
                w: 200,
            },
        };
    },
    components: {
        //   ImgComparisonSlider,
    },
    props: ["photo1", "photo2"],
    setup() {
        //
    },
    mounted() {
        // var x, i;
        // /* Find all elements with an "overlay" class: */
        // x = document.getElementsByClassName("img-comp-overlay");
        // for (i = 0; i < x.length; i++) {
        //     /* Once for each "overlay" element:
        //         pass the "overlay" element as a parameter when executing the compareImages function: */
        //     this.compareImages(x[i]);
        // }
        var img, w, h;
        /* Get the width and height of the img element */
        this.w = img.offsetWidth;
        h = img.offsetHeight;
        /* Set the width of the img element to 50%: */
        img.style.width = this.w / 2 + "px";
        /* Create slider: */
        this.slider = document.createElement("DIV");
        this.slider.setAttribute("class", "img-comp-slider");
        /* Insert slider */
        img.parentElement.insertBefore(this.slider, img);
        /* Position the slider in the middle: */
        this.slider.style.top = h / 2 - this.slider.offsetHeight / 2 + "px";
        this.slider.style.left =
            this.w / 2 - this.slider.offsetWidth / 2 + "px";
        /* Execute a function when the mouse button is pressed: */
        this.compareImages(this.$refs["rightImage"]);
    },
    methods: {
        compareImages(img) {
            this.slider.addEventListener("mousedown", this.slideReady);
            /* And another function when the mouse button is released: */
            window.addEventListener("mouseup", this.slideFinish);
            /* Or touched (for touch screens: */
            this.slider.addEventListener("touchstart", this.slideReady);
            /* And released (for touch screens: */
            window.addEventListener("touchend", this.slideFinish);
        },
        slideReady(e) {
            /* Prevent any other actions that may occur when moving over the image: */
            e.preventDefault();
            /* The slider is now this.clicked and ready to move: */
            this.clicked = 1;
            /* Execute a function when the slider is moved: */
            console.log("registering");
            window.addEventListener("mousemove", this.slideMove);
            window.addEventListener("touchmove", this.slideMove);
        },
        slideFinish() {
            /* The slider is no longer this.clicked: */
            this.clicked = 0;
        },
        slideMove(e) {
            // if (e.target.tagName.toLowerCase() !== "img") {
            //     return;
            // }
            var pos;
            /* If the slider is no longer this.clicked, exit this function: */
            if (this.clicked == 0) return false;
            /* Get the cursor's x position: */
            pos = this.getCursorPos(e);
            /* Prevent the slider from being positioned outside the image: */
            if (pos < 0) pos = 0;
            if (pos > this.w) pos = this.w;
            /* Execute a function that will resize the overlay image according to the cursor: */
            this.slide(pos);
        },
        getCursorPos(e) {
            var a,
                x = 0;
            e = e || window.event;
            console.log(e);
            /* Get the x positions of the image: */
            a = this.$refs["rightImage"].getBoundingClientRect();
            /* Calculate the cursor's x coordinate, relative to the image: */
            console.log(e.pageX);
            console.log(a.left);
            console.log(e.target);
            x = e.pageX - a.left;
            /* Consider any page scrolling: */
            x = x - window.pageXOffset;
            return x;
        },
        slide(x) {
            /* Resize the image: */
            this.$refs["rightImage"].style.width = x + "px";
            /* Position the slider: */
            this.slider.style.left =
                this.$refs["rightImage"].offsetWidth -
                this.slider.offsetWidth / 2 +
                "px";
        },
    },
    layout: MainLayout,
};
</script>
