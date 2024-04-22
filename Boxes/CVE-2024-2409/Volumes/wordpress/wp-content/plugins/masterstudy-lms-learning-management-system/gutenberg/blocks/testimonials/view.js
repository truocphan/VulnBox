import Swiper from "swiper";
import { Pagination } from "swiper/modules";
// import Swiper and modules styles
import "swiper/css";
import "swiper/css/pagination";
import { renderToString } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faUser } from "@fortawesome/free-solid-svg-icons";

new Swiper(".swiper", {
  modules: [Pagination],
  grabCursor: true,
  keyboard: true,
  slidesPerView: 1,
  initialSlide: 0,
  freeMode: true,
  slidesPerColumn: 1,
  pagination: {
    el: ".wp-block-masterstudy-testimonials__swiper .swiper-pagination",
    clickable: true,
    renderBullet: function (index, className) {
      const slides = masterstudyTestimonialsSlides || [];

      if (slides[index]) {
        const imgUrl = slides[index] ? slides[index].imgUrl : "";
        return renderToString(
          <>
            <span class={className} style={{ display: "flex" }}>
              {imgUrl && <img src={imgUrl} alt={`reviewer-avatar-${index}`} />}
              {!imgUrl && <FontAwesomeIcon icon={faUser} />}
            </span>
            {slides.length === 1 && (
              <span class={className} style={{ display: "none" }}></span>
            )}
          </>,
        );
      } else {
        return `<span class="${className}"></span>`;
      }
    },
  },
});
