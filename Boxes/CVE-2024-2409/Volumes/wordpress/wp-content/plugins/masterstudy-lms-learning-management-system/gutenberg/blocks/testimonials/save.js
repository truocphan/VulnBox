import { useBlockStyle } from "../../common/hooks";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faQuoteRight, faStar } from "@fortawesome/free-solid-svg-icons";
import { RichText, useBlockProps } from "@wordpress/block-editor";

export default function Save({ attributes }) {
  const { title, slides } = attributes;

  const { blockClassName, blockStyleObject } = useBlockStyle("testimonials", {
    titleColor: attributes.titleColor,
    ratingColor: attributes.ratingColor,
    textColor: attributes.textColor,
    reviewerColor: attributes.reviewerColor,
    bgColor: attributes.bgColor,
    iconBgColor: attributes.iconBgColor,
    iconColor: attributes.iconColor,
    avatarIconColor: attributes.avatarIconColor,
    avatarBgColor: attributes.avatarBgColor,
    activeAvatarBgColor: attributes.activeAvatarBgColor,
    avatarBorderColor: attributes.avatarBorderColor,
  });

  const blockProps = useBlockProps.save({
    className: `${blockClassName} ${blockClassName}-${attributes.clientId}`,
    style: blockStyleObject,
  });

  return (
    <div {...blockProps}>
      <div className={`${blockClassName}__icon`}>
        <FontAwesomeIcon icon={faQuoteRight} />
      </div>
      <RichText.Content
        value={title}
        tagName="p"
        className={`${blockClassName}__title`}
      />

      <div className={`swiper ${blockClassName}__swiper`}>
        <div className="swiper-wrapper">
          {slides.map((slide, index) => (
            <div
              className={`swiper-slide ${
                index === 0 ? "swiper-slide-active" : ""
              }`}
            >
              <div className="wp-block-masterstudy-testimonials__item">
                <div className="wp-block-masterstudy-testimonials__rating">
                  <FontAwesomeIcon icon={faStar} />
                  <FontAwesomeIcon icon={faStar} />
                  <FontAwesomeIcon icon={faStar} />
                  <FontAwesomeIcon icon={faStar} />
                  <FontAwesomeIcon icon={faStar} />
                </div>
                <RichText.Content
                  value={slide.content}
                  tagName="p"
                  className={`${blockClassName}__review`}
                />
                {slide.reviewer && (
                  <RichText.Content
                    value={slide.reviewer}
                    tagName="p"
                    className="wp-block-masterstudy-testimonials__reviewer"
                  />
                )}
              </div>
            </div>
          ))}
        </div>
        <div className="swiper-pagination"></div>
      </div>
      <script>{`var masterstudyTestimonialsSlides=${JSON.stringify(
        slides,
      )}`}</script>
    </div>
  );
}
