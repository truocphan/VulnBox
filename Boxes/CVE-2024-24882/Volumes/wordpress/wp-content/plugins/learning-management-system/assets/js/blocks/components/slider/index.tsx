import { useInstanceId } from '@wordpress/compose';
import { useRef, useState } from '@wordpress/element';
import classnames from 'classnames';
import React from 'react';
import useClickOutside from '../../hooks/useClickOutside';
import { useDeviceType } from '../../hooks/useDeviceType';
import DeviceSelector from '../device-selector';
import './editor.scss';

interface PropsType {
	value: any;
	onChange: CallableFunction;
	min?: number;
	max?: number;
	defaultUnit?: string;
	label?: string;
	responsive?: boolean;
	step?: number;
	units?: string[];
	inline?: boolean;
	showUnit?: boolean;
}

const Slider: React.FC<PropsType> = (props) => {
	const [isOpen, setIsOpen] = useState(false);
	const unitSelectRef = useRef<any>();
	const {
		value: total = {},
		onChange,
		responsive = false,
		label,
		units = [],
		min = -Infinity,
		max = Infinity,
		step = 0.01,
		inline = false,
		defaultUnit = 'px',
		showUnit = false,
	} = props;
	const devices = {
		desktop: 'Desktop',
		tablet: 'Tablet',
		mobile: 'Mobile',
	};
	const id = useInstanceId(Slider);
	const [deviceType] = useDeviceType();

	useClickOutside(unitSelectRef, () => setIsOpen(false));

	const getUnit = () => {
		if (total) {
			if (responsive) {
				return total[deviceType]
					? total[deviceType].unit
						? total[deviceType].unit
						: defaultUnit
					: defaultUnit;
			}
			return total.unit || defaultUnit;
		}
		return defaultUnit;
	};

	const getValue = () => {
		if (total && Object.keys(total).length > 0) {
			if (responsive) {
				if (units.length > 0) {
					return total[deviceType]
						? total[deviceType].value || 0 === total[deviceType].value
							? total[deviceType].value
							: ''
						: '';
				}
				return total[deviceType] || 0 === total[deviceType]
					? total[deviceType]
					: '';
			}
			return total.value || 0 === total.value ? total.value : '';
		}
		return total || 0 === total ? total : '';
	};

	const inputAttrs = () => {
		let inputMin = min,
			inputMax = max,
			inputStep = step;

		if ('%' === getUnit() || 'vh' === getUnit() || 'vw' === getUnit()) {
			inputMin = 0;
			inputMax = 100;
		}

		if ('em' === getUnit() || 'rem' === getUnit()) {
			inputMin = 0;
			inputMax = 20;
		}

		if (units.length > 0 && 'px' === getUnit()) {
			inputStep = 1;
		}

		return { min: inputMin, max: inputMax, step: inputStep };
	};

	const setSettings = (val: any, prop: string) => {
		val = Number.isNaN(val) ? undefined : val;
		val = val < inputAttrs().min ? inputAttrs().min : val;
		val = val > inputAttrs().max ? inputAttrs().max : val;
		let data = units.length > 0 ? { [prop]: val } : val;

		if (units.length > 0 && 'unit' === prop) {
			data.value = '';
		}

		data =
			units.length > 0
				? Object.assign({}, responsive ? total[deviceType] || {} : total, data)
				: val;

		if (
			units.length > 0 &&
			(!data.unit || !total[deviceType] || !total[deviceType].unit)
		) {
			data.unit = data.unit || defaultUnit;
		}

		onChange(
			data.unit || responsive
				? Object.assign({}, total, responsive ? { [deviceType]: data } : data)
				: data
		);

		setIsOpen(false);
	};

	return (
		<div
			className={classnames('masteriyo-control', 'masteriyo-slider', {
				'masteriyo-responsive': responsive,
				'masteriyo-inline': !responsive && !units && inline,
			})}
		>
			<div className="masteriyo-control-head masteriyo-slider-head">
				{label && (
					<label
						htmlFor={`masteriyo-range-${id}`}
						className="masteriyo-control-label masteriyo-slider-label"
					>
						{label}
					</label>
				)}
				{responsive && <DeviceSelector />}
				{units.length > 0 && (
					<div className="masteriyo-units" ref={unitSelectRef}>
						<button
							className="masteriyo-units-btn"
							onClick={() => setIsOpen(!isOpen)}
							aria-expanded={isOpen}
						>
							{getUnit()}
						</button>
						{units && units.length > 1 && isOpen && (
							<ul className="masteriyo-units-menu" aria-hidden={!isOpen}>
								{units
									.filter((unit) => unit !== getUnit())
									.map((unit) => (
										<li className="masteriyo-unit" key={unit}>
											<button onClick={() => setSettings(unit, 'unit')}>
												{unit}
											</button>
										</li>
									))}
							</ul>
						)}
					</div>
				)}
				{units.length === 0 && showUnit && (
					<div className="masteriyo-units" ref={unitSelectRef}>
						<button
							className="masteriyo-units-btn"
							onClick={() => setIsOpen(!isOpen)}
							aria-expanded={isOpen}
						>
							{getUnit()}
						</button>
					</div>
				)}
			</div>
			<div className="masteriyo-control-body masteriyo-slider-body">
				{responsive ? (
					Object.keys(devices).map(
						(deviceKey) =>
							deviceKey === deviceType && (
								<div key={deviceKey} className="masteriyo-slider-container">
									<input
										type="range"
										value={getValue()}
										onChange={(e) =>
											setSettings(parseFloat(e.currentTarget.value), 'value')
										}
										{...inputAttrs()}
									/>
									<input
										id={`masteriyo-range-${id}`}
										type="number"
										value={getValue()}
										onChange={(e) =>
											setSettings(parseFloat(e.currentTarget.value), 'value')
										}
									/>
								</div>
							)
					)
				) : (
					<div className="masteriyo-slider-container">
						<input
							type="range"
							value={getValue()}
							onChange={(e) =>
								setSettings(parseFloat(e.currentTarget.value), 'value')
							}
							{...inputAttrs()}
						/>
						<input
							id={`masteriyo-range-${id}`}
							type="number"
							value={getValue()}
							onChange={(e) =>
								setSettings(parseFloat(e.currentTarget.value), 'value')
							}
						/>
					</div>
				)}
			</div>
		</div>
	);
};

export default Slider;
