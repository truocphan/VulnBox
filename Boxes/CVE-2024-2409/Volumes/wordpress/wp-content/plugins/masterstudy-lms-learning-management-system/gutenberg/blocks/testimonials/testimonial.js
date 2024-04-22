import { __ } from "@wordpress/i18n";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faStar } from "@fortawesome/free-solid-svg-icons";
import { RichText } from "@wordpress/block-editor";

const TestimonialSlide = ({ slideIndex, slide, slides, setAttributes }) => {
  const onContentChange = (content) => {
    const updatedSlides = [...slides];
    updatedSlides[slideIndex] = { ...slide, content };
    setAttributes({ slides: updatedSlides });
  };

  const onReviewerChange = (reviewer) => {
    const updatedSlides = [...slides];
    updatedSlides[slideIndex] = { ...slide, reviewer };
    setAttributes({ slides: updatedSlides });
  };

  return (
    <>
      <div className="wp-block-masterstudy-testimonials__item">
        <div className="wp-block-masterstudy-testimonials__rating">
          <FontAwesomeIcon icon={faStar} />
          <FontAwesomeIcon icon={faStar} />
          <FontAwesomeIcon icon={faStar} />
          <FontAwesomeIcon icon={faStar} />
          <FontAwesomeIcon icon={faStar} />
        </div>
        <RichText
          value={slide.content}
          identifier={`testimonial-content-${slide.id}`}
          tagName="p"
          withoutInteractiveFormatting
          className="wp-block-masterstudy-testimonials__review"
          onChange={(content) => onContentChange(content)}
          placeholder={__(
            "Add testimonial text…",
            "masterstudy-lms-learning-management-system",
          )}
        />
        <RichText
          value={slide.reviewer}
          identifier={`testimonial-reviewer-${slide.id}`}
          tagName="p"
          withoutInteractiveFormatting
          className="wp-block-masterstudy-testimonials__reviewer"
          onChange={(reviewer) => onReviewerChange(reviewer)}
          placeholder={__(
            "Add reviewer…",
            "masterstudy-lms-learning-management-system",
          )}
        />
      </div>
    </>
  );
};

export default TestimonialSlide;
