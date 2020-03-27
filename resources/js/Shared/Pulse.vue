<template>
    <div class="px-5">
        <div class="dot-typing"></div>
    </div>
</template>

<style>
    .dot-typing {
        position: relative;
        left: -9999px;
        width: 10px;
        height: 10px;
        border-radius: 5px;
        background-color: #fff;
        color: #fff;
        box-shadow: 9984px 0 0 0 #fff, 9999px 0 0 0 #fff, 10014px 0 0 0 #fff;
        animation: dot-typing 1s infinite linear;
    }

    @keyframes dot-typing {
        0% {
            box-shadow: 9984px 0 0 0 #fff, 9999px 0 0 0 #fff, 10014px 0 0 0 #fff;
        }
        16.667% {
            box-shadow: 9984px -10px 0 0 #fff, 9999px 0 0 0 #fff, 10014px 0 0 0 #fff;
        }
        33.333% {
            box-shadow: 9984px 0 0 0 #fff, 9999px 0 0 0 #fff, 10014px 0 0 0 #fff;
        }
        50% {
            box-shadow: 9984px 0 0 0 #fff, 9999px -10px 0 0 #fff, 10014px 0 0 0 #fff;
        }
        66.667% {
            box-shadow: 9984px 0 0 0 #fff, 9999px 0 0 0 #fff, 10014px 0 0 0 #fff;
        }
        83.333% {
            box-shadow: 9984px 0 0 0 #fff, 9999px 0 0 0 #fff, 10014px -10px 0 0 #fff;
        }
        100% {
            box-shadow: 9984px 0 0 0 #fff, 9999px 0 0 0 #fff, 10014px 0 0 0 #fff;
        }
    }
</style>