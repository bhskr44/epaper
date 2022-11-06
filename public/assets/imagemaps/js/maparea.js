const canvasContainer = document.querySelector("#fp-canvas-container");
const maps = document.querySelector("#fp-map");
const image = document.querySelector("#fp-img");

function getRelativeCoords(event) {
    return { x: event.offsetX, y: event.offsetY };
}
canvasContainer.addEventListener("click", (event) => {
    console.log(getRelativeCoords(event));
});
