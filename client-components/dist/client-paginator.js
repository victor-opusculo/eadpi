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
        totalitems: 0,
        resultsonpage: 20,
        pagenum: 1,
        changepagecallback: null
    };

    const methods =
    {
        changePage(e)
        {
            e.preventDefault();
            const toPage = e.target.getAttribute('data-topage');
            if (typeof this.state.changepagecallback === "function")
                this.state.changepagecallback(toPage);
        }
    };


  const __template = function({ state }) {
    return [  
    ((Math.ceil(state.totalitems / state.resultsonpage) > 0) ? h("ul", {"class": `pagination`}, [
      ((state.pagenum > 1) ? h("li", {"class": `prev`},         h("a", {"href": `#`, "onclick": this.changePage.bind(this), "data-topage": `${state.pagenum - 1}`}, `Anterior`)) : ''),
      ((state.pagenum > 3) ? h("li", {"class": `start`},         h("a", {"href": `#`, "onclick": this.changePage.bind(this), "data-topage": `1`}, `1`)) : ''),
      ((state.pagenum > 3) ? h("li", {"class": `dots`}, `...`) : ''),
      (((state.pagenum - 2) > 0) ? h("li", {},         h("a", {"href": `#`, "onclick": this.changePage.bind(this), "data-topage": `${state.pagenum - 2}`}, `${state.pagenum - 2}`)) : ''),
      (((state.pagenum - 1) > 0) ? h("li", {},         h("a", {"href": `#`, "onclick": this.changePage.bind(this), "data-topage": `${state.pagenum - 1}`}, `${state.pagenum - 1}`)) : ''),
      h("li", {"class": `currentPageNum`},         h("a", {"href": `#`, "onclick": this.changePage.bind(this), "data-topage": `${state.pagenum}`}, `${state.pagenum}`)),
      (((state.pagenum + 1) < (Math.ceil(state.totalitems / state.resultsonpage) + 1)) ? h("li", {},         h("a", {"href": `#`, "onclick": this.changePage.bind(this), "data-topage": `${state.pagenum + 1}`}, `${state.pagenum + 1}`)) : ''),
      (((state.pagenum + 2) < (Math.ceil(state.totalitems / state.resultsonpage) + 1)) ? h("li", {},         h("a", {"href": `#`, "onclick": this.changePage.bind(this), "data-topage": `${state.pagenum + 2}`}, `${state.pagenum + 2}`)) : ''),
      ((state.pagenum < (Math.ceil(state.totalitems / state.resultsonpage) - 2)) ? h("li", {"class": `dots`}, `...`) : ''),
      ((state.pagenum < (Math.ceil(state.totalitems / state.resultsonpage) - 2)) ? h("li", {"class": `end`},         h("a", {"href": `#`, "onclick": this.changePage.bind(this), "data-topage": `${Math.ceil(state.totalitems / state.resultsonpage)}`}, `${Math.ceil(state.totalitems / state.resultsonpage)}`)) : ''),
      ((state.pagenum < Math.ceil(state.totalitems / state.resultsonpage)) ? h("li", {"class": `next`},         h("a", {"href": `#`, "onclick": this.changePage.bind(this), "data-topage": `${state.pagenum + 1}`}, `Próxima`)) : '')
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

  
