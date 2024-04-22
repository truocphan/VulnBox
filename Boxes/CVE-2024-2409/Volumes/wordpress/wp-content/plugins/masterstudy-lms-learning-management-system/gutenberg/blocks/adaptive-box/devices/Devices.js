import DeviceSettings from "./settings/DeviceSettings";
import { setDeviceAttribute } from "../../../common/devices";

const Devices = ({ device, attributes, setAttributes }) => {
  const onChangeDeviceCssProperty = (device) => (properties) => {
    setDeviceAttribute(device, attributes, setAttributes, properties);
  };
  return (
    <DeviceSettings
      device={device}
      onChangeCssProperty={onChangeDeviceCssProperty(device)}
      type={attributes.display}
      values={attributes[device] || {}}
    />
  );
};

export default Devices;
