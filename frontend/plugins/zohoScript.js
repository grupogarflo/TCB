export default ({ app }) => {
  ;(function (d, w) {
    w.$zoho = w.$zoho || {}
    w.$zoho.salesiq = w.$zoho.salesiq || {
      widgetcode:
        '1073f4ca0b13d6a7e885c59c0d06cff76e649ce6a3bae22bdbd773abbed2f7c1',
      values: {},
      ready() {},
    }
    // var d = document;
    const s = d.createElement('script')
    const div = d.createElement('div')
    div.id = 'zsiqwidget'
    s.type = 'text/javascript'
    s.id = 'zsiqscript'
    s.defer = true
    s.src = 'https://salesiq.zoho.com/widget'
    const t = d.getElementsByTagName('script')[0]
    const b = d.getElementsByTagName('body')[0]
    t.parentNode.insertBefore(s, t)
    // d.write("<div id='zsiqwidget'></div>");
    b.appendChild(div)
  })(document, window)
}
