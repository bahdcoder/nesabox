<template>
    <div>
        <svg
            :width="width"
            :height="height"
            v-if="icon === 'linode'"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 512 512"
        >
            <path
                fill="#6bbf32"
                d="M283.389 312.972l7.65 120.41 156.57-121.131 4.31-108.357z"
            />
            <path
                fill="#88d652"
                d="M267.759 66.832l-119.17 50.106 65 376.359 77.45-59.915z"
            />
            <path
                fill="#3c8819"
                d="M148.589 116.938l-86.67-50.096 81.25 376.359 70.42 50.096z"
            />
            <path
                d="M377.191 166.974l-100.156 45.36-6.985 127.169 87.344-56.535-1.538 81.284-80.29 62.117 30.952 14.029 155.796-120.533 5.062-127.191-90.185-25.7zm55.706 137.674l-46.589 36.044 1.465-77.388 48.007-31.073-2.883 72.417z"
            />
            <path
                d="M304.221 404.2l-5.296-83.387-16.702-262.969L182.146 0 44.623 57.809l85.12 394.275L213.968 512l92.551-71.603-2.298-36.197zM254.133 88.865l5.798 91.288-77.813 42.757-16.71-96.749 88.725-37.296zM83.734 96.796l51.274 29.636 15.465 89.545-47.297-29.123-19.442-90.058zm28.227 130.747l45.331 27.912 14.624 84.674-41.56-27.38-18.395-85.206zm44.643 206.786l-17.194-79.641 39.517 26.034 13.682 79.221-36.005-25.614zm30.92-180.109l74.51-40.942 5.834 91.853-64.356 41.656-15.988-92.567zm36.605 211.944l-15.059-87.19 60.98-39.47 5.517 86.865-51.438 39.795z"
                fill="#383838"
            />
        </svg>

        <img
            :width="width"
            :height="height"
            v-if="icon === 'vultr'"
            src="/assets/images/vultr.svg"
            alt=""
        />

        <img
            :width="width"
            :height="height"
            v-if="icon === 'digital-ocean'"
            src="/assets/images/digital-ocean.svg"
            alt=""
        />

        <svg
            :width="width"
            :height="height"
            v-if="icon === 'cross-circle'"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                d="M10 14L12 12M12 12L14 10M12 12L10 10M12 12L14 14M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                stroke="#fff"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            />
        </svg>

        <svg
            fill="none"
            :width="width"
            :height="height"
            viewBox="0 0 24 24"
            v-if="icon === 'check-circle'"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                stroke="#fff"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            />
        </svg>

        <img
            :width="width"
            :height="height"
            alt="ubuntu-logo"
            v-if="icon === 'custom'"
            src="https://res.cloudinary.com/bahdcoder/image/upload/v1563480490/ubuntu_ymonj9.png"
        />
    </div>
</template>

<script>
export default {
    props: {
        width: {
            required: false,
            default: 40
        },
        height: {
            required: false,
            default: 40
        },
        icon: {
            required: true
        }
    }
}
</script>
