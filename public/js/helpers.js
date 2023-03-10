function log(message) {
  console.log(message);
}

function delay(fun, timeOut = 5000) {
  setTimeout(fun, timeOut);
}

function redirectTo(url) {
  window.location.href = url;
}

function formDataToJson(data) {
  return JSON.stringify(Array.from(data.entries()).reduce((map = {}, [key, value]) => {
    return {
      ...map,
      [key]: map[key] ? [...map[key], value] : value,
    };
  }, {}));
}

function toggleResponseMessageShowing(presentationElement, message = '', succeed = false, show = false) {

  if (!presentationElement) return;

  presentationElement.classList.remove('d-block');
  presentationElement.classList.add('d-none');
  presentationElement.innerHTML = message;

  if (show && succeed) {
    presentationElement.classList.remove('d-none');
    presentationElement.classList.remove('alert-danger');
    presentationElement.classList.add('d-block');
    presentationElement.classList.add('alert-info');
  }
  else if (show && !succeed) {
    presentationElement.classList.remove('d-none');
    presentationElement.classList.remove('alert-info');
    presentationElement.classList.add('d-block');
    presentationElement.classList.add('alert-danger');
  }
}

export {
  log,
  delay,
  redirectTo,
  formDataToJson,
  toggleResponseMessageShowing,
}