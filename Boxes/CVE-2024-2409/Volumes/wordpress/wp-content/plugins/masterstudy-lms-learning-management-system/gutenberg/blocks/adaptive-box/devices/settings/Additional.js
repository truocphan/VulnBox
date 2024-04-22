import { __ } from "@wordpress/i18n";
import {
  PanelRow,
  Flex,
  FlexItem,
  Tooltip,
  Button,
  CheckboxControl,
  ToggleControl,
  __experimentalInputControl as InputControl,
  __experimentalUnitControl as UnitControl,
} from "@wordpress/components";
import { Fragment } from "@wordpress/element";
import {
  arrowDown,
  arrowRight,
} from "@wordpress/icons";
import {
  JustifyContentStartIcon,
  JustifyContentCenterIcon,
  JustifyContentEndIcon,
  JustifyContentBetweenIcon,
  JustifyContentAroundIcon,
} from "../icons";

const Additional = ({ device, values, onChangeCssProperty, type }) => {
  return (
    <Fragment>
      <PanelRow>
        <Flex>
          <FlexItem style={{ width: "100%" }}>
            {__("Additional settings", "masterstudy-lms-learning-management-system")}
          </FlexItem>
        </Flex>
      </PanelRow>
      {type === "flex" && (
        <>
          <PanelRow>
            <Flex>
              <FlexItem style={{ width: "45%" }}>
                <label
                  style={{
                    fontSize: "11px",
                    fontWeight: 500,
                  }}
                >
                  {__(
                    "Orientation",
                    "masterstudy-lms-learning-management-system",
                  )}
                </label>
                <div
                  className="story-buttons-container"
                  style={{ marginTop: "8px" }}
                >
                  <Tooltip position="top right" text="Row">
                    <Button
                      icon={arrowRight}
                      onClick={() =>
                        onChangeCssProperty({
                          type: "string",
                          cssProperty: "flexDirection",
                          value: "row",
                        })
                      }
                      /** default value */
                      size="small"
                      isPressed={
                        // needs for set the default value
                        !values?.flexDirection || values.flexDirection === "row"
                      }
                    />
                  </Tooltip>
                  <Tooltip position="top right" text="Column">
                    <Button
                      icon={arrowDown}
                      onClick={() =>
                        onChangeCssProperty({
                          type: "string",
                          cssProperty: "flexDirection",
                          value: "column",
                        })
                      }
                      size="small"
                      isPressed={
                        values?.flexDirection &&
                        values.flexDirection === "column"
                      }
                    />
                  </Tooltip>
                  <Tooltip position="top right" text="Row reverse">
                    <Button
                      style={{
                        transform: "rotate(180deg)",
                      }}
                      icon={arrowRight}
                      onClick={() =>
                        onChangeCssProperty({
                          type: "string",
                          cssProperty: "flexDirection",
                          value: "row-reverse",
                        })
                      }
                      size="small"
                      isPressed={
                        values?.flexDirection &&
                        values.flexDirection === "row-reverse"
                      }
                    />
                  </Tooltip>
                  <Tooltip position="top left" text="Column reverse">
                    <Button
                      style={{
                        transform: "rotate(180deg)",
                      }}
                      icon={arrowDown}
                      onClick={() =>
                        onChangeCssProperty({
                          type: "string",
                          cssProperty: "flexDirection",
                          value: "column-reverse",
                        })
                      }
                      size="small"
                      isPressed={
                        values?.flexDirection &&
                        values.flexDirection === "column-reverse"
                      }
                    />
                  </Tooltip>
                </div>
              </FlexItem>
            </Flex>
          </PanelRow>
          <PanelRow>
            <Flex>
              <FlexItem style={{ width: "55%" }}>
                <label
                  style={{
                    fontSize: "11px",
                    fontWeight: 500,
                  }}
                >
                  {__(
                    "Justification",
                    "masterstudy-lms-learning-management-system",
                  )}
                </label>
                <div
                  className="story-buttons-container"
                  style={{ marginTop: "8px" }}
                >
                  <Tooltip position="top right" text="Flex start">
                    <Button
                      icon={JustifyContentStartIcon}
                      onClick={() =>
                        onChangeCssProperty({
                          type: "string",
                          cssProperty: "justifyContent",
                          value:
                            values?.justifyContent &&
                            values.justifyContent === "flex-start"
                              ? undefined
                              : "flex-start",
                        })
                      }
                      size="small"
                      isPressed={
                        values?.justifyContent &&
                        values.justifyContent === "flex-start"
                      }
                    />
                  </Tooltip>
                  <Tooltip position="top center" text="Center">
                    <Button
                      icon={JustifyContentCenterIcon}
                      onClick={() =>
                        onChangeCssProperty({
                          type: "string",
                          cssProperty: "justifyContent",
                          value:
                            values?.justifyContent &&
                            values.justifyContent === "center"
                              ? undefined
                              : "center",
                        })
                      }
                      size="small"
                      isPressed={
                        values?.justifyContent &&
                        values.justifyContent === "center"
                      }
                    />
                  </Tooltip>
                  <Tooltip position="top center" text="Flex end">
                    <Button
                      icon={JustifyContentEndIcon}
                      onClick={() =>
                        onChangeCssProperty({
                          type: "string",
                          cssProperty: "justifyContent",
                          value:
                            values?.justifyContent &&
                            values.justifyContent === "flex-end"
                              ? undefined
                              : "flex-end",
                        })
                      }
                      size="small"
                      isPressed={
                        values?.justifyContent &&
                        values.justifyContent === "flex-end"
                      }
                    />
                  </Tooltip>
                  <Tooltip position="top center" text="Space Between">
                    <Button
                      icon={JustifyContentBetweenIcon}
                      onClick={() =>
                        onChangeCssProperty({
                          type: "string",
                          cssProperty: "justifyContent",
                          value:
                            values?.justifyContent &&
                            values.justifyContent === "space-between"
                              ? undefined
                              : "space-between",
                        })
                      }
                      size="small"
                      isPressed={
                        values?.justifyContent &&
                        values.justifyContent === "space-between"
                      }
                    />
                  </Tooltip>
                  <Tooltip position="top left" text="Space around">
                    <Button
                      icon={JustifyContentAroundIcon}
                      onClick={() =>
                        onChangeCssProperty({
                          type: "string",
                          cssProperty: "justifyContent",
                          value:
                            values?.justifyContent &&
                            values.justifyContent === "space-around"
                              ? undefined
                              : "space-around",
                        })
                      }
                      size="small"
                      isPressed={
                        values?.justifyContent &&
                        values.justifyContent === "space-around"
                      }
                    />
                  </Tooltip>
                </div>
              </FlexItem>
              <FlexItem style={{ width: "45%" }}>
                <label
                  style={{
                    fontSize: "11px",
                    fontWeight: 500,
                  }}
                >
                  {__(
                    "Align items",
                    "masterstudy-lms-learning-management-system",
                  )}
                </label>
                <div
                  className="story-buttons-container"
                  style={{ marginTop: "8px" }}
                >
                  <Tooltip position="top right" text="Flex start">
                    <Button
                      style={{
                        transform: "rotate(90deg)",
                      }}
                      icon={JustifyContentStartIcon}
                      onClick={() =>
                        onChangeCssProperty({
                          type: "string",
                          cssProperty: "alignItems",
                          value:
                            values?.alignItems &&
                            values.alignItems === "flex-start"
                              ? undefined // needs for reset the checked value
                              : "flex-start",
                        })
                      }
                      /** default value */
                      size="small"
                      isPressed={
                        values?.alignItems && values.alignItems === "flex-start"
                      }
                    />
                  </Tooltip>
                  <Tooltip position="top right" text="Center">
                    <Button
                      style={{
                        transform: "rotate(90deg)",
                      }}
                      icon={JustifyContentCenterIcon}
                      onClick={() =>
                        onChangeCssProperty({
                          type: "string",
                          cssProperty: "alignItems",
                          value:
                            values?.alignItems && values.alignItems === "center"
                              ? undefined // needs for reset the checked value
                              : "center",
                        })
                      }
                      size="small"
                      isPressed={
                        values?.alignItems && values.alignItems === "center"
                      }
                    />
                  </Tooltip>
                  <Tooltip position="top right" text="Flex end">
                    <Button
                      style={{
                        transform: "rotate(90deg)",
                      }}
                      icon={JustifyContentEndIcon}
                      onClick={() =>
                        onChangeCssProperty({
                          type: "string",
                          cssProperty: "alignItems",
                          value:
                            values?.alignItems &&
                            values.alignItems === "flex-end"
                              ? undefined // needs for reset the checked value
                              : "flex-end",
                        })
                      }
                      size="small"
                      isPressed={
                        values?.alignItems && values.alignItems === "flex-end"
                      }
                    />
                  </Tooltip>
                  <Tooltip position="top left" text="Baseline">
                    <Button
                      style={{
                        transform: "rotate(90deg)",
                      }}
                      icon={JustifyContentAroundIcon}
                      onClick={() =>
                        onChangeCssProperty({
                          type: "string",
                          cssProperty: "alignItems",
                          value:
                            values?.alignItems &&
                            values.alignItems === "baseline"
                              ? undefined // needs for reset the checked value
                              : "baseline",
                        })
                      }
                      size="small"
                      isPressed={
                        values?.alignItems && values.alignItems === "baseline"
                      }
                    />
                  </Tooltip>
                </div>
              </FlexItem>
            </Flex>
          </PanelRow>
        </>
      )}
      <PanelRow>
        <Flex>
          <FlexItem style={{ width: "50%" }}>
            <InputControl
              label="Flex grow"
              max={99}
              min={0}
              type="number"
              onChange={(value) =>
                onChangeCssProperty({
                  type: "string",
                  cssProperty: "flexGrow",
                  value,
                })
              }
              size="default"
              step="1"
              value={values?.flexGrow}
            />
          </FlexItem>
          <FlexItem style={{ width: "50%" }}>
            <InputControl
              label="Flex shrink"
              max={99}
              min={0}
              type="number"
              onChange={(value) =>
                onChangeCssProperty({
                  type: "string",
                  cssProperty: "flexShrink",
                  value,
                })
              }
              size="default"
              step="1"
              value={values?.flexShrink}
            />
          </FlexItem>
        </Flex>
      </PanelRow>
      <PanelRow>
        <Flex>
          <FlexItem style={{ width: "50%" }}>
            <UnitControl
              label={__(
                "Flex basis",
                "masterstudy-lms-learning-management-system",
              )}
              onChange={(value) =>
                onChangeCssProperty({
                  type: "string",
                  cssProperty: "flexBasis",
                  value,
                })
              }
              value={values?.flexBasis}
            />
          </FlexItem>
          <FlexItem style={{ width: "50%" }}>
            <InputControl
              label="Flex order"
              max={99}
              min={0}
              type="number"
              onChange={(value) =>
                onChangeCssProperty({
                  type: "string",
                  cssProperty: "order",
                  value,
                })
              }
              size="default"
              step="1"
              value={values?.order}
            />
          </FlexItem>
        </Flex>
      </PanelRow>
      {type === "flex" && (
        <PanelRow>
          <Flex>
            <FlexItem style={{ width: "50%" }}>
              <ToggleControl
                label="Enable flex-wrap"
                checked={values?.flexWrap && values.flexWrap === "wrap"}
                onChange={(e) =>
                  onChangeCssProperty({
                    type: "string",
                    cssProperty: "flexWrap",
                    value: e ? "wrap" : undefined,
                  })
                }
              />
            </FlexItem>
          </Flex>
        </PanelRow>
      )}
      <PanelRow>
        <Flex>
          <FlexItem style={{ width: "50%" }}>
            <UnitControl
              label={__(
                "Min width",
                "masterstudy-lms-learning-management-system",
              )}
              onChange={(value) =>
                onChangeCssProperty({
                  type: "string",
                  cssProperty: "minWidth",
                  value,
                })
              }
              value={values?.minWidth}
            />
          </FlexItem>
          {type === "flex" && (
            <FlexItem style={{ width: "50%" }}>
              <UnitControl
                label={__(
                  "Block spacing",
                  "masterstudy-lms-learning-management-system",
                )}
                onChange={(value) =>
                  onChangeCssProperty({
                    type: "string",
                    cssProperty: "gap",
                    value: value !== "string" ? value : "",
                  })
                }
                value={values?.gap}
              />
            </FlexItem>
          )}
        </Flex>
      </PanelRow>
      <PanelRow>
        <Flex>
          <FlexItem style={{ width: "50%" }}>
            <UnitControl
              label={__(
                "Max width",
                "masterstudy-lms-learning-management-system",
              )}
              onChange={(value) =>
                onChangeCssProperty({
                  type: "string",
                  cssProperty: "maxWidth",
                  value,
                })
              }
              value={values?.maxWidth}
            />
          </FlexItem>
          <FlexItem style={{ width: "50%", paddingTop: "24px" }}>
            <ToggleControl
              label="None"
              checked={!!values?.maxWidth && values.maxWidth === "none"}
              onChange={(e) =>
                onChangeCssProperty({
                  type: "string",
                  cssProperty: "maxWidth",
                  value: e ? "none" : undefined,
                })
              }
            />
          </FlexItem>
        </Flex>
      </PanelRow>
      <PanelRow>
        <Flex>
          <FlexItem style={{ width: "50%" }}>
            <UnitControl
              label={__(
                "Min height",
                "masterstudy-lms-learning-management-system",
              )}
              onChange={(value) =>
                onChangeCssProperty({
                  type: "string",
                  cssProperty: "minHeight",
                  value,
                })
              }
              value={values?.minHeight}
            />
          </FlexItem>
          <FlexItem style={{ width: "50%" }}>
            <UnitControl
              label={__(
                "Max height",
                "masterstudy-lms-learning-management-system",
              )}
              onChange={(value) =>
                onChangeCssProperty({
                  type: "string",
                  cssProperty: "maxHeight",
                  value,
                })
              }
              value={values?.maxHeight}
            />
          </FlexItem>
        </Flex>
      </PanelRow>
      <PanelRow>
        <Flex>
          <FlexItem style={{ width: "80%", paddingBlockStart: "33px" }}>
            <CheckboxControl
              label={
                __("Hide on ", "masterstudy-lms-learning-management-system") +
                " " +
                device.toLowerCase()
              }
              checked={values?.display && values.display === "none"}
              onChange={(e) =>
                onChangeCssProperty({
                  type: "string",
                  cssProperty: "display",
                  value: e ? "none" : undefined,
                })
              }
            />
            <CheckboxControl
              label={
                __("Hide background image", "masterstudy-lms-learning-management-system")
              }
              checked={values?.bgImage && values.bgImage === 'none' }
              onChange={(e) =>
                onChangeCssProperty({
                  type: "string",
                  cssProperty: "bgImage",
                  value: e ? 'none' : undefined,
                })
              }
            />
          </FlexItem>
        </Flex>
      </PanelRow>
    </Fragment>
  );
};
export default Additional;
