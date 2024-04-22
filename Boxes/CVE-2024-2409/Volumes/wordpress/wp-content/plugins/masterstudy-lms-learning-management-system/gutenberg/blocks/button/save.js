import { RichText, InnerBlocks, useBlockProps } from "@wordpress/block-editor";
import { useBlockStyle } from "../../common/hooks";

export default function Save({ attributes }) {
  const { linkTarget, rel, text, url, enableIcon } = attributes;

  let properties = {};

  if (linkTarget) {
    properties.target = linkTarget;
  }

  if (rel) {
    properties.rel = rel;
  }

  const Tag = url ? "a" : "button";
  const href = {};

  if (url) {
    href["href"] = url;
  }

  const { blockClassName, blockStyleObject } = useBlockStyle("button", {
    color: attributes.color,
    bgColor: attributes.bgColor,
    hoverColor: attributes.hoverColor,
    bgHoverColor: attributes.bgHoverColor,
    borderColor: attributes.borderColor,
  });

  const blockProps = useBlockProps.save({
    ...href,
    className: `${blockClassName} ${blockClassName}-${attributes.clientId} icon-${attributes.iconPosition}`,
    ...properties,
    style: blockStyleObject,
  });

  return (
    <Tag {...blockProps}>
      <RichText.Content
        tagName="span"
        value={text}
        className="wp-block-masterstudy-button__text"
      />
      {enableIcon && <InnerBlocks.Content />}
    </Tag>
  );
}
