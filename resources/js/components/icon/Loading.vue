<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 100 100"
    preserveAspectRatio="xMidYMid"
    width="200"
    height="200"
    style="
      shape-rendering: auto;
      display: block;
      /* background: rgb(255, 255, 255); */
    "
    xmlns:xlink="http://www.w3.org/1999/xlink"
  >
    <g>
      <circle
        stroke-width="2"
        stroke="#cb72df"
        fill="none"
        r="0"
        cy="50"
        cx="50"
      >
        <animate
          begin="0s"
          calcMode="spline"
          keySplines="0 0.2 0.8 1"
          keyTimes="0;1"
          values="0;40"
          dur="1s"
          repeatCount="indefinite"
          attributeName="r"
        ></animate>
        <animate
          begin="0s"
          calcMode="spline"
          keySplines="0.2 0 0.8 1"
          keyTimes="0;1"
          values="1;0"
          dur="1s"
          repeatCount="indefinite"
          attributeName="opacity"
        ></animate>
      </circle>
      <circle
        stroke-width="2"
        stroke="#a446f0"
        fill="none"
        r="0"
        cy="50"
        cx="50"
      >
        <animate
          begin="-0.5s"
          calcMode="spline"
          keySplines="0 0.2 0.8 1"
          keyTimes="0;1"
          values="0;40"
          dur="1s"
          repeatCount="indefinite"
          attributeName="r"
        ></animate>
        <animate
          begin="-0.5s"
          calcMode="spline"
          keySplines="0.2 0 0.8 1"
          keyTimes="0;1"
          values="1;0"
          dur="1s"
          repeatCount="indefinite"
          attributeName="opacity"
        ></animate>
      </circle>
      <g></g>
    </g>
  </svg>
</template>

<script setup></script>
