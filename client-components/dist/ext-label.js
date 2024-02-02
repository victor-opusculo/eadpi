 // Lego version 1.0.0
  import { h, Component } from './lego.min.js'
   
    import { render } from './lego.min.js';
   
    Component.prototype.render = function(state)
    {
       const childrenHtml = Array.from(this.children);
       this.__state.slotId = `slot_${performance.now().toString().replace('.','')}_${Math.floor(Math.random() * 1000)}`;
   
      this.setState(state);
      if(!this.__isConnected) return
   
      const rendered = render([
        this.vdom({ state: this.__state }),
        this.vstyle({ state: this.__state }),
      ], this.document);
   
      const slot = this.document.querySelector(`#${this.__state.slotId}`);
      if (slot)
         for (const c of childrenHtml)
             slot.appendChild(c);
            
      return rendered;
    };

  
    const state = 
    {
        lineBreak: false,
        reverse: false,
        labelBold: false,
        label: "...",
        slotId: ''
    }


  const __template = function({ state }) {
    return [  
    h("label", {"class": `flex m-2 ${state.lineBreak ? 'flex-col' : 'flex-row items-center'}`}, [
      ((!state.reverse) ? h("span", {}, [
        h("span", {"class": `shrink mr-2 text-base ${state.labelBold ? 'font-bold' : ''}`}, `${state.label}: `),
        h("span", {"class": `grow text-base flex flex-row flex-wrap`}, [
          h("slot", {"id": `${state.slotId}`}, "")
        ])
      ]) : ''),
      ((state.reverse) ? h("span", {}, [
        h("span", {"class": `text-base`}, [
          h("slot", {"id": `${state.slotId}`}, "")
        ]),
        h("span", {"class": `ml-2 text-base ${state.labelBold ? 'font-bold' : ''}`}, `${state.label}: `)
      ]) : '')
    ])
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

  
