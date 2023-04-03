export default ({ app }) => {
  ;(function (d, w) {
    w.$zoho = w.$zoho || {}
    w.$zoho.salesiq = w.$zoho.salesiq || {
      widgetcode:
        '69608eea7c8757242d491bf2ec084711d4d45e084ef830ec4cad0c36df8f1927',
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
