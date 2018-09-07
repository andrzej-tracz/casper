export function extractErrors(errors) {
  const messages = {};

  if (errors && Object.keys(errors).length) {
    Object.keys(errors).forEach(key => {
      messages[key] = errors[key][0] || 'This field is not valid';
    });
  }

  return messages;
}
