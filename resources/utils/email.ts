export function validate(email: string): boolean {
	const pattern = /^[\w.-]+@[\w.-]+\.\w+$/;

	return pattern.test(email);
}