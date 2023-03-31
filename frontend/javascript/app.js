function clickDelete() {
  var result = confirm("本当に削除してもよろしいですか？");
  if (result == true) {
    return true;
  } else {
    return false;
  }
}
