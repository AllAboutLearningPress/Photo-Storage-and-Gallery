
<template >
    <div class="image-view-wrapper">
        <div class="image-view">
            <div class="sidebar-backdrop"></div>
            <div class="image-view__picture-area">
                <div
                    class="image-view__toolbar toolbar"
                    style="background-color: transparent"
                >
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
                    <div class="img-comp-container">
                        <div
                            ref="rightPhoto"
                            class="img-comp-img img-comp-fixed"
                        >
                            <img
                                class="right-photo-img"
                                :src="rightPhoto.src"
                            />
                        </div>
                        <div
                            ref="leftPhoto"
                            class="img-comp-img img-comp-overlay"
                        >
                            <img
                                v-on:load="positionSlider"
                                :src="leftPhoto.src"
                            />
                        </div>
                        <div class="slider-parent" ref="slider">
                            <div class="img-comp-slider"></div>
                        </div>
                    </div>
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
    position: fixed;

    /* max-width: 1024px;
    max-height: 1024px; */
    padding: 0 10px 0 10px;
}
.img-comp-container {
    position: relative;
    width: 100%;
    height: 100%;
}

.img-comp-fixed {
    position: relative;
}
.img-comp-fixed img {
    height: auto;
    max-width: none;
    max-height: 100vh;
    object-fit: contain;
}
@media only screen and (max-width: 1024px) {
    .img-comp-fixed img {
        width: 100vw;
    }
}
.img-comp-overlay {
    width: 100%;
    position: absolute;
    top: 0;
    left: 0;
    overflow: hidden;
    height: inherit;
    /* border-right: 2px solid white; */
}
.img-comp-overlay img {
    height: inherit;
}
.img-comp-img img {
    display: block;
}
.slider-parent {
    display: none;
    background-color: white;
    width: 4px;
    height: 100%;
    position: absolute;
    top: 0;
}
.img-comp-slider {
    display: block;
    position: absolute;
    top: 50%;
    left: -18px;
    transform: translateY(-50%);
    z-index: 9;
    width: 40px;
    height: 40px;
    opacity: 1;
    background-image: url('data:image/svg+xml,%3csvg xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512"%3e %3cdefs%3e %3cfilter id="custom-arrows-a"%3e %3cfeColorMatrix in="SourceGraphic" values="0 0 0 0 1.000000 0 0 0 0 1.000000 0 0 0 0 1.000000 0 0 0 1.000000 0"/%3e %3c/filter%3e %3c/defs%3e %3cg fill="%23e2e2e2" fill-rule="evenodd" filter="url%28%23custom-arrows-a%29"%3e %3cpath fill="%23000" fill-rule="nonzero" d="M11.44 225.39L336.56 225.39C340.706504 225.38742 344.485424 223.011082 346.284179 219.275044 348.082933 215.539006 347.583887 211.102996 345 207.86L182.44 4.06C180.39264 1.49344455 177.288122-.00133928631 174.005-.00133928631 170.721878-.00133928631 167.61736 1.49344455 165.57 4.06L3 207.86C.416112605 211.102996-.0829331302 215.539006 1.7158212 219.275044 3.51457553 223.011082 7.29349632 225.38742 11.44 225.39zM336.56 286.61L11.44 286.61C7.29349632 286.61258 3.51457553 288.988918 1.7158212 292.724956-.0829331302 296.460994.416112605 300.897004 3 304.14L165.56 507.94C167.60736 510.506555 170.711878 512.001339 173.995 512.001339 177.278122 512.001339 180.38264 510.506555 182.43 507.94L345 304.14C347.582769 300.898415 348.0826 296.464701 346.286357 292.729453 344.490113 288.994205 340.714699 286.616413 336.57 286.61L336.56 286.61z" transform="rotate%28-90 215 215%29"/%3e %3c/g%3e%3c/svg%3e');
    background-position: 50% 50%;
    background-repeat: no-repeat;
    background-size: contain;
}
</style>
<script>
import MainLayout from "../Layouts/MainLayout.vue";
import UploadIcon from "../Components/UploadIcon.vue";
import BackButton from "../Components/BackButton.vue";
import HomeButton from "../Components/HomeButton.vue";
import { addEventListener } from "@/util.js";
//import { ImgComparisonSlider } from "@img-comparison-slider/vue";

export default {
    components: {
        //   ImgComparisonSlider,
        UploadIcon,
        HomeButton,
        BackButton,
    },
    props: ["leftPhoto", "rightPhoto", "height"],
    layout: MainLayout,
    data() {
        return {
            clicked: false,
            style: this.genStyle(),
            w: null,
            rmListeners: [],
        };
    },
    created() {},
    mounted() {
        /* Position the slider in the middle if orientiation
        changes or screen resizes*/
        this.rmListeners = [
            // addEventListener(window, "load", this.positionSlider),

            // addEventListener(
            //     document.querySelector(".right-photo-img"),
            //     "load",
            //     this.positionSlider
            // ),
            addEventListener(window, "orientationchange", this.setSlider),
            addEventListener(window, "resize", this.setSlider),

            /* Execute a function when the mouse button is pressed: */
            addEventListener(
                this.$refs["slider"],
                "mousedown",
                this.slideReady
            ),
            /* And another function when the mouse button is released: */
            addEventListener(document, "mouseup", this.slideFinish),
            /* Or touched (for touch screens: */
            addEventListener(
                this.$refs["slider"],
                "touchstart",
                this.slideReady
            ),
            /* And released (for touch screens: */
            addEventListener(document, "touchend", this.slideFinish),
        ];
    },
    methods: {
        /** Functin is called on left image load event
         */
        positionSlider(e) {
            console.log(e);

            let rightImage = document.querySelector(".right-photo-img");
            if (rightImage.complete) {
                // right image was also loaded. So can position the slider now
                // the slider is loaded after the images finished loading
                this.$refs["slider"].style.display = "block";

                this.setSlider();
            } else {
                this.rmListeners.push(
                    addEventListener(rightImage, "load", this.positionSlider)
                );
            }
        },
        setSlider(e) {
            /* Get the width and height of the img element */
            this.width = this.$refs["rightPhoto"].offsetWidth;

            /* Set the width of the img element to 50%: */
            this.$refs["leftPhoto"].style.width = this.width / 2 + "px";

            let image = this.$refs["rightPhoto"].querySelector("img");
            if (image) {
                this.$refs["slider"].style.top =
                    image.offsetHeight / 2 -
                    this.$refs["slider"].offsetHeight / 2 +
                    "px";
                this.$refs["slider"].style.left =
                    this.width / 2 -
                    this.$refs["slider"].offsetWidth / 2 +
                    "px";
            }
        },
        genStyle() {
            return;
            return `width: ${this.leftPhoto.width}px`; //height:${this.leftPhoto.height}px
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
            if (pos > this.width) pos = this.width;
            /* Execute a function that will resize the overlay image according to the cursor: */
            this.slide(pos);
        },
        getCursorPos(e) {
            var a,
                x = 0;

            let touch = null;
            // for touchscreen touch event
            if (e.touches) e = e.touches[0];

            /* Get the x positions of the image: */
            a = this.$refs["leftPhoto"].getBoundingClientRect();
            /* Calculate the cursor's x coordinate, relative to the image: */
            x = e.pageX - a.left;
            /* Consider any page scrolling: */
            x = x - window.pageXOffset;
            return x;
        },
        slide(x) {
            /* Resize the image: */
            this.$refs["leftPhoto"].style.width = x + "px";
            /* Position the slider: */
            this.$refs["slider"].style.left =
                this.$refs["leftPhoto"].offsetWidth -
                this.$refs["slider"].offsetWidth / 2 +
                "px";
        },
    },
    beforeUnmount() {
        // removing the event listeners before unmounting
        console.log("removing listener");
        this.rmListeners.forEach((fn) => fn());
    },
};
</script>
