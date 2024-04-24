import { useInstanceId } from '@wordpress/compose';
import { useEffect, useRef, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import classnames from 'classnames';
import React from 'react';
import useClickOutside from '../../hooks/useClickOutside';
import { useDeviceType } from '../../hooks/useDeviceType';
import DeviceSelector from '../device-selector';
import { Icon } from '../index';
import './editor.scss';

interface PropsType {
	min?: number;
	step?: number;
	max?: number;
	units: string[];
	defaultUnit?: string;
	onChange: any;
	value: {
		left?: number;
		right?: number;
		top?: number;
		bottom?: number;
		unit?: string;

		desktop?: {
			left: number;
			right: number;
			top: number;
			bottom: number;
			unit: string;
		};
		tablet?: {
			left: number;
			right: number;
			top: number;
			bottom: number;
			unit: string;
		};
		mobile?: {
			left: number;
			right: number;
			top: number;
			bottom: number;
			unit: string;
		};

		lock?: boolean;
	};
	label?: string;
	responsive?: boolean;
	type?: string;
	dimensionLabels?: {
		top: string;
		right: string;
		bottom: string;
		left: string;
	};
}

const Dimensions: React.FC<PropsType> = (props) => {
	const [isOpen, setIsOpen] = useState(false);
	const unitSelectRef = useRef<any>();
	const {
		value: total,
		onChange,
		responsive = false,
		label,
		units,
		min = -Infinity,
		max = Infinity,
		step = 0.01,
		defaultUnit = 'px',
		type = '',
		dimensionLabels = {
			top: __('Top', 'masteriyo'),
			right: __('Right', 'masteriyo'),
			bottom: __('Bottom', 'masteriyo'),
			left: __('Left', 'masteriyo'),
		},
	} = props;
	const devices = {
		desktop: 'Desktop',
		tablet: 'Tablet',
		mobile: 'Mobile',
	};
	const dimensionProps = dimensionLabels;
	const instanceId = useInstanceId(Dimensions);
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

	const getValue = (key: string) => {
		if (Object.keys(total).length > 0) {
			if (responsive) {
				return total[deviceType]
					? total[deviceType][key] || 0 === total[deviceType][key]
						? total[deviceType][key]
						: ''
					: '';
			}
			return total[key] || 0 === total[key] ? total[key] : '';
		}
		return '';
	};

	const inputAttrs = () => {
		let inputMin = min,
			inputMax = max,
			inputStep = step;

		if ('%' === getUnit() || 'vh' === getUnit() || 'vw' === getUnit()) {
			inputMin = 'margin' === type ? -100 : 0;
			inputMax = 100;
		}

		if ('em' === getUnit() || 'rem' === getUnit()) {
			inputMin = 'margin' === type ? -20 : 0;
			inputMax = 20;
		}

		if ('px' === getUnit()) {
			inputMin = 'margin' === type ? -inputMax : 0;
			inputStep = 1;
		}

		return { min: inputMin, max: inputMax, step: inputStep };
	};

	const setSettings = (val: any, prop: string = '') => {
		val = Number.isNaN(val) ? (Boolean(getValue('lock')) ? undefined : 0) : val;
		val = val < inputAttrs().min ? inputAttrs().min : val;
		val = val > inputAttrs().max ? inputAttrs().max : val;
		let data: any =
			Boolean(getValue('lock')) && 'unit' !== prop && 'lock' !== prop
				? { top: val, right: val, bottom: val, left: val }
				: { [prop]: val };

		if ('unit' === prop) {
			data.top = undefined;
			data.right = undefined;
			data.bottom = undefined;
			data.left = undefined;
		}

		data = Object.assign(
			{},
			responsive ? total[deviceType] || {} : total,
			data
		);

		if (!data.unit || !total[deviceType] || !total[deviceType].unit) {
			data.unit = data.unit || defaultUnit;
		}

		if (!data.lock || !total[deviceType] || !total[deviceType].lock) {
			data.lock = data.lock || false;
		}

		onChange(
			Object.assign({}, total, responsive ? { [deviceType]: data } : data)
		);
		setIsOpen(false);
	};
	const lockValue = getValue('lock');

	useEffect(() => {
		if (!lockValue) {
			return;
		}

		if (!responsive) {
			let allProp: any[] = [];
			let maxProp;

			for (const prop of Object.keys(dimensionProps)) {
				allProp = [...allProp, total[prop] || ''];

				if (allProp.length === 4) {
					maxProp = allProp.every((v) => '' === v)
						? undefined
						: Math.max(...allProp);

					total.top = maxProp;
					total.right = maxProp;
					total.bottom = maxProp;
					total.left = maxProp;
				}
			}
			onChange(Object.assign({}, total));
		} else {
			let allProp: any[] = [];
			let maxProp;

			if (total[deviceType]) {
				for (const prop of Object.keys(dimensionProps)) {
					allProp = [...allProp, total[deviceType][prop] || ''];

					if (allProp.length === 4) {
						maxProp = allProp.every((v) => '' === v)
							? undefined
							: Math.max(...allProp);

						total[deviceType].top = maxProp;
						total[deviceType].right = maxProp;
						total[deviceType].bottom = maxProp;
						total[deviceType].left = maxProp;
					}
				}
			}
			onChange(Object.assign({}, total));
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [lockValue, deviceType, responsive, dimensionProps, total, onChange]);

	return (
		<div
			className={classnames('masteriyo-control', 'masteriyo-dimensions', {
				'masteriyo-responsive': responsive,
			})}
		>
			<div className="masteriyo-control-head masteriyo-dimensions-head">
				{label && (
					<label
						htmlFor={`masteriyo-dimension-top-${instanceId}`}
						className="masteriyo-control-label masteriyo-dimensions-label"
					>
						{label}
					</label>
				)}
				{responsive && <DeviceSelector />}
				{units && (
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
			</div>
			<div className="masteriyo-control-body masteriyo-dimension-body">
				{responsive ? (
					Object.keys(devices).map(
						(deviceKey) =>
							deviceKey === deviceType && (
								<div key={deviceKey} className="masteriyo-dimensions-container">
									{Object.keys(dimensionProps).map((dimensionProp) => (
										<span key={dimensionProp} className="masteriyo-dimension">
											<input
												id={`masteriyo-dimension-${dimensionProp}-${instanceId}`}
												value={getValue(dimensionProp)}
												type="number"
												onChange={(e) =>
													setSettings(parseFloat(e.target.value), dimensionProp)
												}
											/>
											<label
												htmlFor={`masteriyo-dimension-${dimensionProp}-${instanceId}`}
											>
												{dimensionProps[dimensionProp]}
											</label>
										</span>
									))}
									<button
										className={classnames('masteriyo-dimensions-lock', {
											'is-lock': Boolean(getValue('lock')),
										})}
										onClick={() =>
											setSettings(!Boolean(getValue('lock')), 'lock')
										}
									>
										<Icon
											type="controlIcon"
											name={Boolean(getValue('lock')) ? 'lock' : 'un-link'}
											size={16}
										/>
									</button>
								</div>
							)
					)
				) : (
					<div className="masteriyo-dimensions-container">
						{Object.keys(dimensionProps).map((dimensionProp) => (
							<span key={dimensionProp} className="masteriyo-dimension">
								<input
									id={`masteriyo-dimension-${dimensionProp}-${instanceId}`}
									value={getValue(dimensionProp)}
									type="number"
									onChange={(e) =>
										setSettings(parseFloat(e.target.value), dimensionProp)
									}
								/>
								<label
									htmlFor={`masteriyo-dimension-${dimensionProp}-${instanceId}`}
								>
									{dimensionProps[dimensionProp]}
								</label>
							</span>
						))}
						<button
							className={classnames('masteriyo-dimensions-lock', {
								'is-lock': Boolean(getValue('lock')),
							})}
							onClick={() => setSettings(!Boolean(getValue('lock')), 'lock')}
						>
							<Icon
								type="controlIcon"
								name={Boolean(getValue('lock')) ? 'lock' : 'un-link'}
								size={16}
							/>
						</button>
					</div>
				)}
			</div>
		</div>
	);
};

export default Dimensions;
