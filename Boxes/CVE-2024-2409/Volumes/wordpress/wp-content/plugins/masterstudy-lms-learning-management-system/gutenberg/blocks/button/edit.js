import "./editor.scss";
import { __ } from "@wordpress/i18n";
import { useMergeRefs } from "@wordpress/compose";
import { useEffect, useState, useRef, Fragment } from "@wordpress/element";
import {
  Panel,
  PanelBody,
  ToolbarButton,
  Popover,
  ToggleControl,
  __experimentalToggleGroupControl as ToggleGroupControl,
  __experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from "@wordpress/components";
import {
  RichText,
  useBlockProps,
  InspectorControls,
  PanelColorSettings,
  InnerBlocks,
  BlockControls,
  __experimentalLinkControl as LinkControl,
} from "@wordpress/block-editor";
import { link, linkOff } from "@wordpress/icons";
import { useBlockClientId, useBlockStyle } from "../../common/hooks";
import { displayShortcut, isKeyboardEvent } from "@wordpress/keycodes";

const NEW_TAB_REL = "noreferrer noopener";
const TEMPLATE = [["masterstudy/icon"]];

export default function Edit({
  attributes,
  setAttributes,
  isSelected,
  clientId,
}) {
  useBlockClientId(attributes, setAttributes, clientId);

  const { linkTarget, rel, text, url, enableIcon } = attributes;

  const onToggleOpenInNewTab = (value) => {
    const newLinkTarget = value ? "_blank" : "";

    let updatedRel = rel;
    if (newLinkTarget && !rel) {
      updatedRel = NEW_TAB_REL;
    } else if (!newLinkTarget && rel === NEW_TAB_REL) {
      updatedRel = "";
    }

    setAttributes({
      linkTarget: newLinkTarget,
      rel: updatedRel,
    });
  };

  const setButtonText = (newText) => {
    setAttributes({ text: newText.replace(/<\/?a[^>]*>/g, "") });
  };

  const onKeyDown = (event) => {
    if (isKeyboardEvent.primary(event, "k")) {
      startEditing(event);
    } else if (isKeyboardEvent.primaryShift(event, "k")) {
      unlink();
      richTextRef && richTextRef.current && richTextRef.current.focus();
    }
  };

  const unlink = () => {
    setAttributes({ url: "", linkTarget: "", rel: "" });
    setIsEditingURL(false);
  };

  // Button styles
  const { blockClassName, blockStyleVariables } = useBlockStyle("button", {
    color: attributes.color,
    bgColor: attributes.bgColor,
    hoverColor: attributes.hoverColor,
    bgHoverColor: attributes.bgHoverColor,
    borderColor: attributes.borderColor,
  });
  // Use internal state instead of a ref to make sure that the component
  // re-renders when the popover's anchor updates.
  const [popoverAnchor, setPopoverAnchor] = useState(null);

  const ref = useRef();
  const richTextRef = useRef();
  const blockProps = useBlockProps({
    ref: useMergeRefs([setPopoverAnchor, ref]),
    onKeyDown,
    className: `${blockClassName} ${blockClassName}-${attributes.clientId} icon-${attributes.iconPosition}`,
  });

  const [isEditingURL, setIsEditingURL] = useState(false);
  const isURLSet = !!url;
  const opensInNewTab = linkTarget === "_blank";

  const startEditing = (event) => {
    event.preventDefault();
    setIsEditingURL(true);
  };

  useEffect(() => {
    if (!isSelected) setIsEditingURL(false);
  }, [isSelected]);

  return (
    <Fragment>
      <div {...blockProps}>
        <span>
          <RichText
            value={text}
            identifier="text"
            ref={richTextRef}
            withoutInteractiveFormatting
            className="wp-block-masterstudy-button__text"
            onChange={(value) => setButtonText(value)}
            placeholder={__(
              "Add text…",
              "masterstudy-lms-learning-management-system",
            )}
            aria-label={__(
              "Button text",
              "masterstudy-lms-learning-management-system",
            )}
          />
        </span>
        {enableIcon && <InnerBlocks template={TEMPLATE} templateLock="all" />}
      </div>
      <BlockControls group="block">
        {!isURLSet && (
          <ToolbarButton
            name="link"
            icon={link}
            title={__("Link", "masterstudy-lms-learning-management-system")}
            shortcut={displayShortcut.primary("k")}
            onClick={startEditing}
          />
        )}
        {isURLSet && (
          <ToolbarButton
            icon={linkOff}
            title={__("Unlink", "masterstudy-lms-learning-management-system")}
            shortcut={displayShortcut.primaryShift("k")}
            onClick={unlink}
            isActive={true}
          />
        )}
      </BlockControls>
      {isSelected && (isEditingURL || isURLSet) && (
        <Popover
          placement="bottom"
          onClose={() => {
            setIsEditingURL(false);
          }}
          anchor={popoverAnchor}
          focusOnMount={isEditingURL ? "firstElement" : false}
          __unstableSlotName={"__unstable-block-tools-after"}
          shift
        >
          <LinkControl
            className="wp-block-navigation-link__inline-link-input"
            value={{ url, opensInNewTab }}
            onChange={({ url: newURL, opensInNewTab: newOpensInNewTab }) => {
              setAttributes({ url: newURL });

              if (opensInNewTab !== newOpensInNewTab) {
                onToggleOpenInNewTab(newOpensInNewTab);
              }
            }}
            onRemove={() => {
              unlink();
            }}
            forceIsEditingLink={isEditingURL}
          />
        </Popover>
      )}
      <InspectorControls>
        <Panel>
          <div style={{ borderBottom: "1px solid #e0e0e0" }}>
            <PanelBody
              title={__(
                "General settings",
                "masterstudy-lms-learning-management-system",
              )}
            >
              <ToggleControl
                label={__(
                  "Add an icon to button",
                  "masterstudy-lms-learning-management-system",
                )}
                checked={enableIcon}
                onChange={(enableIcon) => setAttributes({ enableIcon })}
              />
              {enableIcon && (
                <ToggleGroupControl
                  isBlock
                  size="default"
                  value={attributes.iconPosition}
                  onChange={(iconPosition) => setAttributes({ iconPosition })}
                  label={__(
                    "Icon position",
                    "masterstudy-lms-learning-management-system",
                  )}
                >
                  <ToggleGroupControlOption
                    value="left"
                    label={__(
                      "Left",
                      "masterstudy-lms-learning-management-system",
                    )}
                  />
                  <ToggleGroupControlOption
                    value="right"
                    label={__(
                      "Right",
                      "masterstudy-lms-learning-management-system",
                    )}
                  />
                </ToggleGroupControl>
              )}
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
      <style>
        {`.${blockClassName}-${attributes.clientId} { ${blockStyleVariables} }`}
      </style>
    </Fragment>
  );
}
