
<template >
    <div class="image-view-wrapper">
        <div class="image-view">
            <div class="sidebar-backdrop"></div>
            <div class="image-view__picture-area">
                <div class="image-view__toolbar toolbar">
                    <div class="toolbar__main">
                        <back-button></back-button>
                        <home-button></home-button>
                    </div>
                    <div class="toolbar__aside">
                        <div class="toolbar__specific-actions">
                            <input
                                type="checkbox"
                                class="
                                    toolbar__specific-actions-toggle
                                    toggler
                                    btn-check
                                "
                                id="toolbar__specific-actions-toggle"
                                autocomplete="off"
                            />
                            <label
                                title="Show more actions"
                                class="btn-subtle btn"
                                for="toolbar__specific-actions-toggle"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    fill="currentColor"
                                    class="
                                        toggler__icon-open
                                        bi bi-three-dots-vertical
                                    "
                                    viewBox="0 0 16 16"
                                >
                                    <path
                                        d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"
                                    />
                                </svg>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    fill="currentColor"
                                    class="toggler__icon-close bi bi-x"
                                    viewBox="0 0 16 16"
                                >
                                    <path
                                        d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"
                                    />
                                </svg>
                                <span class="visually-hidden"
                                    >Show more actions</span
                                >
                            </label>
                        </div>
                        <upload-icon></upload-icon>
                    </div>
                </div>
                <div :style="style" class="comp-container">
                    <div class="img-comp-img">
                        <img
                            ref="rightImage"
                            :src="rightPhoto.src"
                            style="max-width: 100%"
                        />
                    </div>
                    <div ref="leftimage" class="img-comp-img img-comp-overlay">
                        <img :src="leftPhoto.src" />
                    </div>
                    <div ref="slider" class="img-comp-slider"></div>
                </div>
            </div>
        </div>
    </div>
</template>
<style >
.comp-container {
    z-index: 0;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    position: absolute;
    max-width: 100%;
    margin: 0;
}

.img-comp-img {
    position: absolute;
    width: 100%;
    overflow: hidden;
}

.img-comp-img img {
    display: block;
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
import UploadIcon from "../Components/UploadIcon.vue";
import BackButton from "../Components/BackButton.vue";
import HomeButton from "../Components/HomeButton.vue";
//import { ImgComparisonSlider } from "@img-comparison-slider/vue";

export default {
    components: {
        //   ImgComparisonSlider,
        UploadIcon,
        HomeButton,
        BackButton,
    },
    props: ["leftPhoto", "rightPhoto", "height"],

    data() {
        return {
            clicked: false,

            style: this.genStyle(),
            w: null,
        };
    },

    mounted() {
        // document.querySelector(".img-comp-container").style.height =
        //     Math.max(this.leftPhoto.height, this.rightPhoto.height) + "px";

        /* Get the width and height of the img element */
        this.w = this.$refs["leftimage"].offsetWidth;

        /* Set the width of the img element to 50%: */
        this.$refs["leftimage"].style.width = this.w / 2 + "px";

        /* Position the slider in the middle if orientiation
        changes or screen resizes*/
        window.addEventListener("load", this.positionSlider);
        window.addEventListener("orientationchange", this.positionSlider);
        window.addEventListener("resize", this.positionSlider);

        /* Execute a function when the mouse button is pressed: */
        this.$refs["slider"].addEventListener("mousedown", this.slideReady);
        /* And another function when the mouse button is released: */
        window.addEventListener("mouseup", this.slideFinish);
        /* Or touched (for touch screens: */
        this.$refs["slider"].addEventListener("touchstart", this.slideReady);
        /* And released (for touch screens: */
        window.addEventListener("touchend", this.slideFinish);
    },
    methods: {
        positionSlider(e) {
            console.log(e);
            if (this.$refs["rightImage"]) {
                console.log(
                    "img offset ",
                    this.$refs["rightImage"].offsetWidth
                );
                let sliderPos = this.$refs["rightImage"].offsetWidth / 2;
                this.$refs["slider"].style.top =
                    this.$refs["rightImage"].offsetHeight / 2 -
                    this.$refs["slider"].offsetHeight / 2 +
                    "px";
                this.$refs["slider"].style.left =
                    sliderPos - this.$refs["slider"].offsetWidth / 2 + "px";
                // on image
                this.$refs["leftimage"].style.width = sliderPos + "px";
                // this.style = `width: ${this.$refs["rightImage"].offsetWidth}px; height: ${this.$refs["rightImage"].offsetHeight}px`;
            }
        },
        genStyle() {
            return `width: ${this.leftPhoto.width}px; height: ${this.leftPhoto.height}px`;
        },
        slideReady(e) {
            /* Prevent any other actions that may occur when moving over the image: */
            e.preventDefault();
            /* The slider is now this.clicked and ready to move: */
            this.clicked = true;

            /* Execute a function when the slider is moved: */
            window.addEventListener("mousemove", this.slideMove);
            window.addEventListener("touchmove", this.slideMove);
        },
        slideFinish() {
            /* The slider is no longer this.clicked: */
            this.clicked = false;
            // removing the listeners for slide event
            window.removeEventListener("mousemove", this.slideMove);
            window.removeEventListener("touchmove", this.slideMove);
        },
        slideMove(e) {
            var pos;
            /* If the slider is no longer this.clicked, exit this function: */
            if (!this.clicked) return;
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

            // for touchscreen touch event
            if (e.touches) e = e.touches[0];

            /* Get the x positions of the image: */
            a = this.$refs["leftimage"].getBoundingClientRect();
            /* Calculate the cursor's x coordinate, relative to the image: */
            x = e.pageX - a.left;
            /* Consider any page scrolling: */
            x = x - window.pageXOffset;
            return x;
        },
        slide(x) {
            /* Resize the image: */
            this.$refs["leftimage"].style.width = x + "px";
            /* Position the slider: */
            this.$refs["slider"].style.left =
                x - this.$refs["slider"].offsetWidth / 2 + "px";
        },
    },
    layout: MainLayout,
};
</script>
