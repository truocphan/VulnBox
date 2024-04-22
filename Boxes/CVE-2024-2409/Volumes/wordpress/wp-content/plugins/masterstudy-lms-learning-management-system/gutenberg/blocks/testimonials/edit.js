import { __ } from "@wordpress/i18n";
import { useBlockClientId, useBlockStyle } from "../../common/hooks";
import { uniqueId } from "../../common/utils";
import {
  useEffect,
  useState,
  Fragment,
  renderToString,
} from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faUser, faQuoteRight } from "@fortawesome/free-solid-svg-icons";

import { Swiper, SwiperSlide } from "swiper/react";
import { Pagination } from "swiper/modules";
import "swiper/css";
import "swiper/css/pagination";
import "swiper/css/navigation";
import "./editor.scss";

import TestimonialSlide from "./testimonial";

import {
  Panel,
  PanelBody,
  PanelRow,
  ToolbarButton,
  __experimentalToggleGroupControl as ToggleGroupControl,
  __experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from "@wordpress/components";
import {
  RichText,
  useBlockProps,
  InspectorControls,
  PanelColorSettings,
  MediaUpload,
  MediaUploadCheck,
} from "@wordpress/block-editor";

export default function Edit({ attributes, setAttributes, clientId }) {
  const { title, slides } = attributes;
  const [slideCat, setSlideCat] = useState("new");
  const [activeSlide, setActiveSlide] = useState(0);
  const [imageUrl, setImageUrl] = useState("");
  const [swiper, setSwiper] = useState(null);
  const [swiperKey, setSwiperKey] = useState(0);

  useBlockClientId(attributes, setAttributes, clientId);

  useEffect(() => {
    if (0 === slides.length) {
      setAttributes({
        slides: [
          ...attributes.slides,
          { id: uniqueId(), content: "", rating: "", imgUrl: "" },
        ],
      });
    } else {
      setImageUrl(slides[activeSlide].imgUrl);
    }
  }, [attributes.slides, setAttributes, activeSlide]);

  const { blockClassName, blockStyleVariables } = useBlockStyle(
    "testimonials",
    {
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
    },
  );
  const blockProps = useBlockProps({
    className: `${blockClassName} ${blockClassName}-${attributes.clientId}`,
  });

  const pagination = {
    clickable: true,
    renderBullet: function (index, className) {
      let imgUrl = slides[index] ? slides[index].imgUrl : "";
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
    },
  };

  const addSlide = () => {
    const updatedSlides = [
      ...attributes.slides,
      { id: uniqueId(), content: "", rating: "" },
    ];
    setAttributes({ slides: updatedSlides });
    setActiveSlide(updatedSlides.length - 1);
    setTimeout(() => {
      swiper.slideTo(updatedSlides.length);
    }, 100);
  };

  const removeSlide = () => {
    const updatedSlides = slides.filter((_, index) => index !== activeSlide);
    setAttributes({ slides: updatedSlides });

    if (updatedSlides.length < 0) {
      setAttributes({
        slides: [
          ...attributes.slides,
          { id: uniqueId(), content: "", rating: "", imgUrl: "" },
        ],
      });
    } else {
      setActiveSlide(updatedSlides.length - 1);
    }
  };

  const onImageSelect = (image) => {
    if (activeSlide >= 0) {
      let imgUrl = image.url ? image.url : "";
      if (image.sizes.full.url) {
        imgUrl = image.sizes.full.url;
      }
      slides[activeSlide].imgUrl = imgUrl;
      setAttributes({ slides });
      setImageUrl(imgUrl);
      setSwiperKey((prevKey) => prevKey + 1);
    }
  };

  const removeSlideImage = () => {
    if (activeSlide >= 0) {
      slides[activeSlide].imgUrl = "";
      setAttributes({ slides });
      setImageUrl("");
      setSwiperKey((prevKey) => prevKey + 1);
    }
  };

  return (
    <Fragment>
      <InspectorControls>
        <Panel>
          <PanelBody
            title={__(
              "Testimonial slide",
              "masterstudy-lms-learning-management-system",
            )}
          >
            <ToggleGroupControl
              isBlock
              size="default"
              value={slideCat}
              onChange={(cat) => setSlideCat(cat)}
            >
              <ToggleGroupControlOption
                label={__(
                  "New Slide",
                  "masterstudy-lms-learning-management-system",
                )}
                value="new"
              />
              <ToggleGroupControlOption
                label={__(
                  "Current Slide",
                  "masterstudy-lms-learning-management-system",
                )}
                value="current"
              />
            </ToggleGroupControl>

            {slideCat === "new" && (
              <ToolbarButton
                icon="plus"
                onClick={addSlide}
                className={`${blockClassName}__slide--add-btn is-primary`}
              >
                <span>
                  {__(
                    "Add Slide",
                    "masterstudy-lms-learning-management-system",
                  )}
                </span>
              </ToolbarButton>
            )}
            {slideCat === "current" && (
              <>
                <div className={`${blockClassName}__slide-image`}>
                  {imageUrl && (
                    <img
                      src={imageUrl}
                      alt={`reviewer-avatar-${activeSlide}`}
                    />
                  )}
                  {!imageUrl && <FontAwesomeIcon icon={faUser} />}
                </div>
                <MediaUploadCheck>
                  <MediaUpload
                    onSelect={onImageSelect}
                    allowedTypes={["image"]}
                    render={({ open }) => (
                      <PanelRow className={`${blockClassName}__panel-row`}>
                        <ToolbarButton
                          onClick={open}
                          className={`${blockClassName}__slide--add-btn is-primary`}
                        >
                          {!imageUrl && (
                            <span>
                              {__(
                                "Add image",
                                "masterstudy-lms-learning-management-system",
                              )}
                            </span>
                          )}
                          {imageUrl && (
                            <span>
                              {__(
                                "Change image",
                                "masterstudy-lms-learning-management-system",
                              )}
                            </span>
                          )}
                        </ToolbarButton>
                        {imageUrl && (
                          <ToolbarButton
                            onClick={removeSlideImage}
                            className={`${blockClassName}__slide--add-btn is-destructive is-primary`}
                          >
                            <span>
                              {__(
                                "Remove image",
                                "masterstudy-lms-learning-management-system",
                              )}
                            </span>
                          </ToolbarButton>
                        )}
                      </PanelRow>
                    )}
                  />
                </MediaUploadCheck>
                <ToolbarButton
                  onClick={removeSlide}
                  className={`${blockClassName}__slide--add-btn is-destructive is-secondary`}
                >
                  <span>
                    {__(
                      "Remove Slide",
                      "masterstudy-lms-learning-management-system",
                    )}
                  </span>
                </ToolbarButton>
              </>
            )}
          </PanelBody>
        </Panel>
        <Panel>
          <div style={{ borderBottom: "1px solid #e0e0e0" }}>
            <PanelColorSettings
              enableAlpha={true}
              disableCustomColors={false}
              __experimentalHasMultipleOrigins
              __experimentalIsRenderedInSidebar
              title={__("Colors", "masterstudy-lms-learning-management-system")}
              colorSettings={[
                {
                  value: attributes.iconColor,
                  onChange: (iconColor) => setAttributes({ iconColor }),
                  label: __(
                    "Icon color",
                    "masterstudy-lms-learning-management-system",
                  ),
                },
                {
                  value: attributes.iconBgColor,
                  onChange: (iconBgColor) => setAttributes({ iconBgColor }),
                  label: __(
                    "Icon background color",
                    "masterstudy-lms-learning-management-system",
                  ),
                },
                {
                  value: attributes.titleColor,
                  onChange: (titleColor) => setAttributes({ titleColor }),
                  label: __(
                    "Title color",
                    "masterstudy-lms-learning-management-system",
                  ),
                },
                {
                  value: attributes.ratingColor,
                  onChange: (ratingColor) => setAttributes({ ratingColor }),
                  label: __(
                    "Rating color",
                    "masterstudy-lms-learning-management-system",
                  ),
                },
                {
                  value: attributes.textColor,
                  onChange: (textColor) => setAttributes({ textColor }),
                  label: __(
                    "Text color",
                    "masterstudy-lms-learning-management-system",
                  ),
                },
                {
                  value: attributes.reviewerColor,
                  onChange: (reviewerColor) => setAttributes({ reviewerColor }),
                  label: __(
                    "Reviewer color",
                    "masterstudy-lms-learning-management-system",
                  ),
                },
                {
                  value: attributes.bgColor,
                  onChange: (bgColor) => setAttributes({ bgColor }),
                  label: __(
                    "Background color",
                    "masterstudy-lms-learning-management-system",
                  ),
                },
                {
                  value: attributes.avatarIconColor,
                  onChange: (avatarIconColor) => setAttributes({ avatarIconColor }),
                  label: __(
                    "Avatar icon color",
                    "masterstudy-lms-learning-management-system",
                  ),
                },
                {
                  value: attributes.avatarBgColor,
                  onChange: (avatarBgColor) => setAttributes({ avatarBgColor }),
                  label: __(
                    "Avatar icon background color",
                    "masterstudy-lms-learning-management-system",
                  ),
                },
                {
                  value: attributes.activeAvatarBgColor,
                  onChange: (activeAvatarBgColor) => setAttributes({ activeAvatarBgColor }),
                  label: __(
                    "Active avatar background color",
                    "masterstudy-lms-learning-management-system",
                  ),
                },
                {
                  value: attributes.avatarBorderColor,
                  onChange: (avatarBorderColor) => setAttributes({ avatarBorderColor }),
                  label: __(
                    "Avatar border color",
                    "masterstudy-lms-learning-management-system",
                  ),
                }
              ]}
            />
          </div>
        </Panel>
      </InspectorControls>

      <div {...blockProps}>
        <div className={`${blockClassName}__icon`}>
          <FontAwesomeIcon icon={faQuoteRight} />
        </div>
        <RichText
          value={title}
          identifier="testimonial-title"
          tagName="p"
          withoutInteractiveFormatting
          className={`${blockClassName}__title`}
          placeholder={__(
            "Add title…",
            "masterstudy-lms-learning-management-system",
          )}
          onChange={(title) => setAttributes({ title })}
        />

        <Swiper
          key={swiperKey}
          pagination={pagination}
          slidesPerView={1}
          grabCursor={false}
          keyboard={false}
          className={`${blockClassName}__swiper`}
          initialSlide={activeSlide}
          modules={[Pagination]}
          onSlideChange={(swiper) => setActiveSlide(swiper.activeIndex)}
          onSwiper={(swiper) => setSwiper(swiper)}
          slidesPerColumn={1}
          mousewheel={false}
          mousewheelControl={false}
          simulateTouch={false}
        >
          {slides.map((slide, index) => (
            <SwiperSlide>
              <TestimonialSlide
                slideIndex={index}
                slide={slide}
                slides={slides}
                setAttributes={setAttributes}
              />
            </SwiperSlide>
          ))}
        </Swiper>
      </div>

      <style>
        {`.${blockClassName}-${attributes.clientId} { ${blockStyleVariables} }`}
      </style>
    </Fragment>
  );
}
