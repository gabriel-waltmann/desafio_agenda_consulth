export const parse = (phone: string): string => {
	return phone.replace(/[\D]/g, "");
};

export const format = (phone: string): string => {
	const phoneString = String(phone).replace(/[\D]/g, "");

	let phoneFormatted = "";
	const first2Digits = phoneString.slice(0, 2);
	const next5Digits = phoneString.slice(2, 7);
	const last4Digits = phoneString.slice(7, 12);

	if (first2Digits) {
		phoneFormatted += `(${first2Digits})`;
	}

	if (next5Digits) {
		phoneFormatted += ` ${next5Digits}`;
	}

	if (last4Digits) {
		phoneFormatted += `-${last4Digits}`;
	}

	return phoneFormatted;
};

export const validate = (phoneNumber: string): boolean => {
	const phone = parse(phoneNumber);
	return phone.length === 11;
}