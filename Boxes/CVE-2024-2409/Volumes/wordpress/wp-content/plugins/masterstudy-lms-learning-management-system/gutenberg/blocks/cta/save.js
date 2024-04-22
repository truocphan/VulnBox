import { InnerBlocks, useBlockProps } from "@wordpress/block-editor";
import { useBlockStyle } from "../../common/hooks";

export default function Save({ attributes }) {
  const {
    isBackgroundImage,
    isBackgroundTransparent,
    backgroundImage,
    textAlign,
  } = attributes;
  // Blocks styles
  const { blockClassName, blockStyleObject } = useBlockStyle("cta", {
    width: attributes.width,
    color: attributes.color,
    bgColor: attributes.bgColor,
    hoverColor: attributes.hoverColor,
    bgHoverColor: attributes.bgHoverColor,
    borderColor: attributes.borderColor,
  });

  if (isBackgroundImage) {
    blockStyleObject["backgroundImage"] = `url(${backgroundImage})`;
  }

  const blockPropClass = [
    `${attributes.width ? `${blockClassName}--hasWidth` : ""}`,
    `${isBackgroundTransparent ? `${blockClassName}--bgTransparent` : ""}`,
    `has-text-align-${textAlign}`,
  ];

  const blockProps = useBlockProps.save({
    className: `${blockClassName}-${attributes.clientId} ${blockPropClass.join(
      " ",
    )}`,
    style: blockStyleObject,
  });

  return (
    <div {...blockProps}>
      <div>
        <InnerBlocks.Content />
      </div>
    </div>
  );
}
