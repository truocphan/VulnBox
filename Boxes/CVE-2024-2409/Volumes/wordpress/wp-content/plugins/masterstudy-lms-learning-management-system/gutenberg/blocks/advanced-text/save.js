import { RichText, useBlockProps } from "@wordpress/block-editor";
import { useBlockStyle } from "../../common/hooks";
import { getDeviceAttributes } from "../../common/devices";

const Save = ({ attributes }) => {
  const { blockStyles, level } = attributes;
  const devices = ["mobile", "tablet", "desktop"];
  let blockClass = "";

  devices.map((device) => {
    const attrStyles = getDeviceAttributes(device, attributes, [
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
    const { blockClassName } = useBlockStyle(
      "advanced-text",
      attrStyles,
      device,
      blockStyles,
    );

    blockClass = blockClassName;
  });

  const blockProps = useBlockProps.save({
    className: `${blockClass} ${blockClass}-${attributes.clientId}`,
    style: blockStyles,
  });

  return (
    <div {...blockProps}>
      <RichText.Content
        tagName={`h${level}`}
        className={`${blockClass}__title`}
        value={attributes.title}
      />
      <RichText.Content
        tagName="p"
        className={`${blockClass}__text`}
        value={attributes.text}
      />
    </div>
  );
};

export default Save;
