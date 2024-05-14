 // Lego version 1.0.0
  import { h, Component } from './lego.min.js'
   
    import { render } from './lego.min.js';
   
    Component.prototype.render = function(state)
    {
      const childs = Array.from(this.childNodes);
      this.__originalChildren = childs.length && !this.__originalChildren?.length ? childs : this.__originalChildren;

       this.__state.slotId = `slot_${performance.now().toString().replace('.','')}_${Math.floor(Math.random() * 1000)}`;
   
      this.setState(state);
      if(!this.__isConnected) return
   
      const rendered = render([
        this.vdom({ state: this.__state }),
        this.vstyle({ state: this.__state }),
      ], this.document);
   
      const slot = this.document.querySelector(`#${this.__state.slotId}`);
      if (slot)
         for (const c of this.__originalChildren)
             slot.appendChild(c);
            
      return rendered;
    };

  
    const state =
    {
        datarows: [],
        columnstohide: [],
        returnidcallback: null,
        selectlinkparamname: 'id'
    };

    const methods = 
    {
        selectClick(e)
        {
            e.preventDefault();
            const param = e.target.getAttribute('data-id');
            this.state.returnidcallback(param);
        },
        
    }; 


  const __template = function({ state }) {
    return [  
    ((!(state.datarows?.length)) ? h("p", {}, `Não há dados disponíveis.`) : ''),
    ((state.datarows?.length > 0) ? h("table", {}, [
      h("thead", {}, [
        h("tr", {}, [
          ((Object.keys(state.datarows[0])).map((header) => (h("th", {}, `
                    ${header}
                `)))),
          ((state.returnidcallback) ? h("th", {}, `Selecionar`) : '')
        ])
      ]),
      h("tbody", {}, [
        ((state.datarows).map((row) => (h("tr", {}, [
          ((Object.keys(row)).map((header) => (h("td", {"data-th": `${header}`}, [
            ((typeof row[header] === 'string') ? h("span", {}, `${row[header]}`) : ''),
            ((typeof row[header] === 'object' && row[header].type === 'image') ? h("img", {"src": `${row[header].src}`, "width": `${row[header].width}`}, "") : '')
          ])))),
          ((state.returnidcallback) ? h("td", {}, [
            h("a", {"class": `link text-lg`, "onclick": this.selectClick.bind(this), "data-th": `Selecionar`, "data-id": `${row[state.selectlinkparamname]}`, "href": `#`}, `Selecionar`)
          ]) : '')
        ]))))
      ])
    ]) : '')
  ]
  }

  const __style = function({ state }) {
    return h('style', {}, `
      
      
    `)
  }

  // -- Lego Core
  export default class Lego extends Component {
    init() {
      this.useShadowDOM = false
      if(typeof state === 'object') this.__state = Object.assign({}, state, this.__state)
      if(typeof methods === 'object') Object.keys(methods).forEach(methodName => this[methodName] = methods[methodName])
      if(typeof connected === 'function') this.connected = connected
      if(typeof setup === 'function') setup.bind(this)()
    }
    get vdom() { return __template }
    get vstyle() { return __style }
  }
  // -- End Lego Core

  
