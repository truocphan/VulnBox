import Padding from "./Padding";
import Margin from "./Margin";
import Gradient from "./Gradient";
import Additional from "./Additional";
import { Fragment } from "@wordpress/element";

const DeviceSettings = ({ device, onChangeCssProperty, type, values }) => {
  return (
    <Fragment>
      <hr style={{ marginBlock: "0.75em" }} />
      <Padding onChangeCssProperty={onChangeCssProperty} values={values} />
      <hr style={{ marginBlock: "0.75em" }} />
      <Margin onChangeCssProperty={onChangeCssProperty} values={values} />
      <hr style={{ marginBlock: "0.75em" }} />
      <Additional
        onChangeCssProperty={onChangeCssProperty}
        values={values}
        type={type}
        device={device}
      />
      <hr style={{ marginBlock: "0.75em" }} />
      <Gradient onChangeCssProperty={onChangeCssProperty} values={values} />
      <hr style={{ marginBlock: "0.75em" }} />
    </Fragment>
  );
};

export default DeviceSettings;
