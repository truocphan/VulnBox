import { useBlockProps } from "@wordpress/block-editor";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { useBlockStyle } from "../../common/hooks";
import { getDeviceAttributes } from "../../common/devices";

export default function save({ attributes }) {
  const { blockStyles, level } = attributes;
  const devices = ["mobile", "tablet", "desktop"];
  let blockClass = "";

  devices.map((device) => {
    const attrStyles = getDeviceAttributes(device, attributes, [
      {
        cssProperty: "width",
        default: "24px",
      },
      {
        cssProperty: "height",
        default: "24px",
      },
    ]);
    const { blockClassName } = useBlockStyle(
      "icon",
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
      <span>
        <FontAwesomeIcon icon={attributes.iconAttr} fill="currentColor" />
      </span>
    </div>
  );
}
