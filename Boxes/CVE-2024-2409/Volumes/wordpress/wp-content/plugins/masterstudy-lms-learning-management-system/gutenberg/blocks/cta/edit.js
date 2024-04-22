import "./editor.scss";
import { __ } from "@wordpress/i18n";
import { Fragment } from "@wordpress/element";
import { useBlockClientId, useBlockStyle } from "../../common/hooks";
import {
  Panel,
  PanelBody,
  PanelRow,
  ToggleControl,
  ToolbarButton,
  __experimentalUnitControl as UnitControl,
} from "@wordpress/components";
import {
  InnerBlocks,
  useBlockProps,
  BlockControls,
  AlignmentControl,
  InspectorControls,
  PanelColorSettings,
  MediaUpload,
} from "@wordpress/block-editor";
import { blockClientID } from "../../common/utils";

const TEMPLATE = [["masterstudy/advanced-text"], ["masterstudy/button"]];

export default function Edit({ attributes, setAttributes, clientId }) {
  const {
    isBackgroundImage,
    isBackgroundTransparent,
    backgroundImage,
    textAlign,
  } = attributes;
  // Set client ID
  useBlockClientId(attributes, setAttributes, clientId);
  // Blocks styles
  const { blockClassName, blockStyleObject } = useBlockStyle("cta", {
    width: attributes.width,
    color: attributes.color,
    bgColor: attributes.bgColor,
    hoverColor: attributes.hoverColor,
    bgHoverColor: attributes.bgHoverColor,
    borderColor: attributes.borderColor,
  });

  const onImageSelect = (image) => {
    setAttributes({ backgroundImage: image.url ? image.url : "" });
  };

  const removeSlideImage = () => {
    setAttributes({ backgroundImage: "" });
  };

  if (isBackgroundImage) {
    blockStyleObject["backgroundImage"] = `url(${backgroundImage})`;
  }

  const blockPropClass = [
    `${attributes.width ? `${blockClassName}--hasWidth` : ""}`,
    `${isBackgroundTransparent ? `${blockClassName}--bgTransparent` : ""}`,
    `has-text-align-${textAlign}`,
  ];

  const blockProps = useBlockProps({
    className: `${blockClassName}-${attributes.clientId} ${blockPropClass.join(
      " ",
    )}`,
    style: blockStyleObject,
  });

  return (
    <Fragment>
      <BlockControls group="block">
        <AlignmentControl
          value={textAlign}
          onChange={(textAlign) => setAttributes({ textAlign })}
        />
      </BlockControls>
      <div {...blockProps}>
        <InnerBlocks
          template={TEMPLATE}
          allowedBlocks={["masterstudy/button", "core/paragraph"]}
        />
      </div>
      <InspectorControls>
        <Panel>
          <div style={{ borderBottom: "1px solid #e0e0e0" }} class="wp-block-masterstudy-cta">
            <PanelBody
              title={__(
                "General settings",
                "masterstudy-lms-learning-management-system",
              )}
            >
              <ToggleControl
                label={__(
                  "Add an image to background",
                  "masterstudy-lms-learning-management-system",
                )}
                checked={isBackgroundImage}
                onChange={(isBackgroundImage) =>
                  setAttributes({ isBackgroundImage })
                }
              />
              {isBackgroundImage && (
                <MediaUpload
                  onSelect={onImageSelect}
                  allowedTypes={["image"]}
                  render={({ open }) => (
                    <PanelRow className={`${blockClassName}__panel-row`}>
                      <ToolbarButton
                        onClick={open}
                        className={`${blockClassName}__image--action is-primary`}
                      >
                        {!backgroundImage && (
                          <span>
                            {__(
                              "Add image",
                              "masterstudy-lms-learning-management-system",
                            )}
                          </span>
                        )}
                        {backgroundImage && (
                          <span>
                            {__(
                              "Change image",
                              "masterstudy-lms-learning-management-system",
                            )}
                          </span>
                        )}
                      </ToolbarButton>
                      {backgroundImage && (
                        <ToolbarButton
                          onClick={removeSlideImage}
                          className={`${blockClassName}__image--action is-destructive is-primary`}
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
              )}
              <ToggleControl
                label={__(
                  "Make background transparent",
                  "masterstudy-lms-learning-management-system",
                )}
                checked={isBackgroundTransparent}
                onChange={(isBackgroundTransparent) =>
                  setAttributes({ isBackgroundTransparent })
                }
              />
              <UnitControl
                label={__(
                  "Width",
                  "masterstudy-lms-learning-management-system",
                )}
                onChange={(width) => setAttributes({ width })}
                value={attributes.width}
              />
            </PanelBody>
          </div>
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
                  value: attributes.color,
                  onChange: (color) => setAttributes({ color }),
                  label: __(
                    "Text color",
                    "masterstudy-lms-learning-management-system",
                  ),
                },
                {
                  value: attributes.hoverColor,
                  onChange: (hoverColor) =>
                    setAttributes({ hoverColor: hoverColor }),
                  label: __(
                    "Text hover",
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
                  value: attributes.bgHoverColor,
                  onChange: (bgHoverColor) =>
                    setAttributes({ bgHoverColor: bgHoverColor }),
                  label: __(
                    "Background hover",
                    "masterstudy-lms-learning-management-system",
                  ),
                },
              ]}
            />
          </div>
        </Panel>
      </InspectorControls>
    </Fragment>
  );
}
