import { __ } from "@wordpress/i18n";
import "./editor.scss";
import {
  DevicesTabControl,
  setDeviceAttribute,
  getDeviceAttributes,
} from "../../common/devices";
import {
  RichText,
  useBlockProps,
  InspectorControls,
  BlockControls,
  AlignmentControl,
  HeadingLevelDropdown,
} from "@wordpress/block-editor";
import { useEffect, useState, Fragment } from "@wordpress/element";
import {
  Panel,
  PanelBody,
  PanelRow,
  FontSizePicker,
  __experimentalBoxControl as BoxControl,
  __experimentalToggleGroupControl as ToggleGroupControl,
  __experimentalToggleGroupControlOptionIcon as ToggleGroupControlOptionIcon,
} from "@wordpress/components";
import { useBlockStyle, useBlockClientId } from "../../common/hooks";
import { FONT_SIZES } from "../../common/constants";

export default function Edit({ attributes, setAttributes, clientId }) {
  const { blockStyles, level } = attributes;
  const [device, setDevice] = useState("desktop");
  const [isTitle, setIsTitle] = useState(false);
  const blockAttributes = getDeviceAttributes(device, attributes, [
    {
      cssProperty: "textAlign",
      default: "left",
    },
    {
      cssProperty: "padding",
      default: 0,
    },
    {
      cssProperty: "margin",
      default: 0,
    },
    {
      cssProperty: "titleFontSize",
      default: "2rem",
    },
    {
      cssProperty: "textFontSize",
      default: "1rem",
    },
    {
      cssProperty: "titleMargin",
      default: "20px 0px",
    },
    {
      cssProperty: "titlePadding",
      default: 0,
    },
    {
      cssProperty: "textMargin",
      default: "15px 0px",
    },
    {
      cssProperty: "textPadding",
      default: 0,
    },
  ]);
  useBlockClientId(attributes, setAttributes, clientId);

  const { blockClassName, accumulatedStyles } = useBlockStyle(
    "advanced-text",
    blockAttributes,
    device,
    attributes.blockStyles,
  );

  const blockProps = useBlockProps({
    className: `${blockClassName} ${blockClassName}-${attributes.clientId}`,
    style: blockStyles,
  });

  useEffect(() => {
    setAttributes({ blockStyles: accumulatedStyles });
  }, [accumulatedStyles]);

  return (
    <Fragment>
      <BlockControls group="block">
        {isTitle && (
          <HeadingLevelDropdown
            value={level}
            onChange={(level) => setAttributes({ level })}
          />
        )}
        <AlignmentControl
          value={blockAttributes.textAlign}
          onChange={(textAlign) =>
            setDeviceAttribute(device, attributes, setAttributes, {
              type: "string",
              cssProperty: "textAlign",
              value: textAlign,
            })
          }
        />
      </BlockControls>
      <InspectorControls>
        <Panel className="masterstudy-components-panel">
          <div style={{ borderTop: "1px solid #e0e0e0" }}>
            <PanelBody
              title={__(
                "General Settings",
                "masterstudy-lms-learning-management-system",
              )}
            >
              <DevicesTabControl device={device} setDevice={setDevice} />
              <ToggleGroupControl
                __nextHasNoMarginBottom
                isBlock
                value={blockAttributes.textAlign}
                label={__(
                  "Alignment",
                  "masterstudy-lms-learning-management-system",
                )}
                onChange={(textAlign) =>
                  setDeviceAttribute(device, attributes, setAttributes, {
                    type: "string",
                    cssProperty: "textAlign",
                    value: textAlign,
                  })
                }
              >
                <ToggleGroupControlOptionIcon
                  icon="editor-alignleft"
                  value="left"
                />
                <ToggleGroupControlOptionIcon
                  icon="editor-aligncenter"
                  value="center"
                />
                <ToggleGroupControlOptionIcon
                  icon="editor-alignright"
                  value="right"
                />
                <ToggleGroupControlOptionIcon
                  icon="editor-justify"
                  value="justify"
                />
              </ToggleGroupControl>
              <PanelRow>
                <BoxControl
                  values={blockAttributes.padding}
                  label={__(
                    "Padding",
                    "masterstudy-lms-learning-management-system",
                  )}
                  onChange={(value) =>
                    setDeviceAttribute(device, attributes, setAttributes, {
                      type: "string",
                      cssProperty: "padding",
                      value,
                    })
                  }
                />
              </PanelRow>
              <PanelRow>
                <BoxControl
                  values={blockAttributes.margin}
                  label={__(
                    "Margin",
                    "masterstudy-lms-learning-management-system",
                  )}
                  onChange={(value) =>
                    setDeviceAttribute(device, attributes, setAttributes, {
                      type: "string",
                      cssProperty: "margin",
                      value,
                    })
                  }
                />
              </PanelRow>
            </PanelBody>
          </div>
          <div style={{ borderTop: "1px solid #e0e0e0" }}>
            <PanelBody
              title={__(
                "Title Settings",
                "masterstudy-lms-learning-management-system",
              )}
            >
              <DevicesTabControl device={device} setDevice={setDevice} />
              <FontSizePicker
                __nextHasNoMarginBottom
                fontSizes={FONT_SIZES}
                label={__(
                  "Font size",
                  "masterstudy-lms-learning-management-system",
                )}
                value={blockAttributes.titleFontSize}
                fallbackFontSize={"1.6rem"}
                withSlider={true}
                onChange={(value) =>
                  setDeviceAttribute(device, attributes, setAttributes, {
                    type: "string",
                    cssProperty: "titleFontSize",
                    value,
                  })
                }
              />
              <PanelRow>
                <BoxControl
                  values={blockAttributes.titlePadding}
                  label={__(
                    "Padding",
                    "masterstudy-lms-learning-management-system",
                  )}
                  onChange={(value) =>
                    setDeviceAttribute(device, attributes, setAttributes, {
                      type: "string",
                      cssProperty: "titlePadding",
                      value,
                    })
                  }
                />
              </PanelRow>
              <PanelRow>
                <BoxControl
                  values={blockAttributes.titleMargin}
                  label={__(
                    "Margin",
                    "masterstudy-lms-learning-management-system",
                  )}
                  onChange={(value) =>
                    setDeviceAttribute(device, attributes, setAttributes, {
                      type: "string",
                      cssProperty: "titleMargin",
                      value,
                    })
                  }
                />
              </PanelRow>
            </PanelBody>
          </div>
          <div style={{ borderTop: "1px solid #e0e0e0" }}>
            <PanelBody
              title={__(
                "Text Settings",
                "masterstudy-lms-learning-management-system",
              )}
            >
              <DevicesTabControl device={device} setDevice={setDevice} />
              <FontSizePicker
                __nextHasNoMarginBottom
                fontSizes={FONT_SIZES}
                label={__(
                  "Font size",
                  "masterstudy-lms-learning-management-system",
                )}
                value={blockAttributes.textFontSize}
                fallbackFontSize={"1rem"}
                withSlider={true}
                onChange={(value) =>
                  setDeviceAttribute(device, attributes, setAttributes, {
                    type: "string",
                    cssProperty: "textFontSize",
                    value,
                  })
                }
              />
              <PanelRow>
                <BoxControl
                  values={blockAttributes.textPadding}
                  label={__(
                    "Padding",
                    "masterstudy-lms-learning-management-system",
                  )}
                  onChange={(value) =>
                    setDeviceAttribute(device, attributes, setAttributes, {
                      type: "string",
                      cssProperty: "textPadding",
                      value,
                    })
                  }
                />
              </PanelRow>
              <PanelRow>
                <BoxControl
                  values={blockAttributes.textMargin}
                  label={__(
                    "Margin",
                    "masterstudy-lms-learning-management-system",
                  )}
                  onChange={(value) =>
                    setDeviceAttribute(device, attributes, setAttributes, {
                      type: "string",
                      cssProperty: "textMargin",
                      value,
                    })
                  }
                />
              </PanelRow>
            </PanelBody>
          </div>
        </Panel>
      </InspectorControls>

      <div {...blockProps}>
        <RichText
          tagName={`h${level}`}
          className={`${blockClassName}__title`}
          onClick={() => {
            setIsTitle(true);
          }}
          value={attributes.title}
          onChange={(title) => setAttributes({ title })}
          placeholder={__(
            "Heading",
            "masterstudy-lms-learning-management-system",
          )}
        />
        <RichText
          tagName="p"
          className={`${blockClassName}__text`}
          value={attributes.text}
          onChange={(text) => setAttributes({ text })}
          placeholder={__("Text", "masterstudy-lms-learning-management-system")}
        />
      </div>
    </Fragment>
  );
}
