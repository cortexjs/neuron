<p></p>
<p></p>
<div></div>
<select style="display:none">
	<option></option>
	<option></option>
	<option></option>
	<option></option>
</select>

<script>

describe('Neuron:lang/enhance', function(){
	describe('DP', function(){
		describe('.mix()', function(){
			it('could mix an object to the receiver', function(){
				var receiver = {
						a: 1,
						b: 2,
						o: {
							a: 1,
							b: 2
						}
					},
					
					sender = {
						b: 22,
						c: 3,
						o: {
							c: 3
						},
						
						d1: {
							a: 1
						},
						
						d2: {
							a: 2
						},
						
						d3: 3
					};
					
				// default to override
				KM.mix(receiver, sender);
					
				expect( receiver.a ).toEqual(1);
				expect( receiver.b ).toEqual(22);
				
				// override
				expect( receiver.o.a ).toBeUndefined();
				expect( receiver.o.c ).toEqual(3);
			});
			
			it('could mix an object to the receiver, excluding the existed member', function(){
				var receiver2 = {
						a: 1,
						b: 2,
						o: {
							a: 1,
							b: 2
						}
					},
					
					sender = {
						b: 22,
						c: 3,
						o: {
							c: 3
						},
						
						d1: {
							a: 1
						},
						
						d2: {
							a: 2
						},
						
						d3: 3
					};
					
				// no override
				KM.mix(receiver2, sender, false);
					
				// no override
				expect( receiver2.b ).toEqual(2);
				expect( receiver2.o.a ).toEqual(1);
			});
			
			it('could mix an object to the receiver, according to a copy list', function(){
				var receiver3 = {
						a: 1,
						b: 2,
						o: {
							a: 1,
							b: 2
						}
					},
					
					sender = {
						b: 22,
						c: 3,
						o: {
							c: 3
						},
						
						d1: {
							a: 1
						},
						
						d2: {
							a: 2
						},
						
						d3: 3
					};
					
				// override by a copy list
				KM.mix(receiver3, sender, true, ['d1', 'd2']);
					
				expect( receiver3.b ).toEqual(2);
				expect( receiver3.d1.a ).toEqual(1);
				expect( receiver3.d3 ).toBeUndefined();
			});
			
			('could mix an object to the receiver, with mixed condition', function(){
				var receiver4 = {
						a: 1,
						b: 2,
						o: {
							a: 1,
							b: 2
						}
					},
					
					sender = {
						b: 22,
						c: 3,
						o: {
							c: 3
						},
						
						d1: {
							a: 1
						},
						
						d2: {
							a: 2
						},
						
						d3: 3
					};
					
				KM.mix(receiver4, sender, false, ['o', 'd1', 'd3']);
				// not override
				expect( receiver4.o.c ).toBeUndefined();
				expect( receiver4.o.a ).toEqual(1);
				
				expect( receiver4.d2 ).toBeUndefined();
				expect( receiver4.d3 ).toEqual(3);
			});
		});
		
		
		describe('.each()', function(){
			it('could iterate an object which created using {}', function(){
				var c = {
						a: 1,
						b: 2
					},
					
					obj_receiver = {};
					
				KM.each(c, function(v, k){
					obj_receiver[k] = v;
				});
				
				expect( obj_receiver.b ).toEqual(2);
			});
			
			it('will ignore the prototype', function(){
				function C(){};
				
				C.prototype = {
					a: 1
				};
				
				var	d = new C,
					obj_receiver2 = {};
					
				d.b = 2;
					
				KM.each(d, function(v, k){
					obj_receiver2[k] = v;
				});
				
				expect( obj_receiver2.a ).toBeUndefined(1);
				expect( obj_receiver2.b ).toEqual(2);
			});
			
			
			it('could iterate an array', function(){
				var a = [1,2],
					arr_receiver = [];
					
				a.a = a;
				
				KM.each(a, function(v, i){
					arr_receiver[i] = v;
				});
				
				expect( arr_receiver[0] ).toEqual(1);
				expect( arr_receiver[1] ).toEqual(2);
			});
		});
		
		
		describe('.clone()', function(){
			
			it('could clone an object', function(){
				// preparing origin data >>>>>>>>>>>>>>>>>>>>>>>>>>>
				var o = {
						a: {
							a: 1,
							b: 2,
							c: {
								d: {
									e: 1
								}
							}
						},
						
						b: {
							a: 11,
							b: 22
						}
					},
					
					receiver;
					
				o.c = o;
				
				// receiver ------------------------------------
				// clone an object
				receiver = KM.clone(o);
				
				// test recursive object
				expect( receiver.c.a.b ).toEqual(2);
				
				// alter original and cloned data
				o.d = 3;
				expect( receiver.d ).toBeUndefined();
				
				// alter recursive object
				o.c.a.c.d.e = 11;
				expect( receiver.c.a.c.d.e ).toEqual(1);
				
				receiver.a.a = 11;
				expect( o.a.a ).toEqual(1);	
			});
			
			it('could clone members of an object into a receiver', function(){
				var o2 = {
						a: {
							a: 1,
							b: 2,
							c: {
								d: {
									e: 1
								}
							}
						},
						
						b: {
							a: 11,
							b: 22
						}
					},
					
					receiver2;
					
				o2.c = o2;
					
				// receiver2 ------------------------------------
				// clone an object into a receiver
				receiver2 = {}
				KM.clone(o2, false, receiver2);
				
				// test recursive object
				expect( receiver2.c.a.b ).toEqual(2);
				
				// alter original and cloned data
				o2.d = 3;
				expect( receiver2.d ).toBeUndefined();
				
				// alter recursive object
				o2.c.a.c.d.e = 11;
				expect( receiver2.c.a.c.d.e ).toEqual(1);
				
				receiver2.a.a = 11;
				expect( o2.a.a ).toEqual(1);
			});
			
			
			it('could clone an array', function(){
				var array = [1, 2, 3, 4],
					cloned;
					
				array.a = array;
					
				cloned = KM.clone(array);
				
				cloned[0] = 11;
				array[2] = 33;
				
				expect(cloned.length).toEqual(4);
				expect(array[0]).toEqual(1);
				expect(cloned[2]).toEqual(3);
				
				// recursive member
				expect(cloned.a[2]).toEqual(3);
			});
			
			it('could clone an mixed object', function(){
				var foo = function(){},
					p = document.getElementsByTagName('p')[0],
					obj = [
						{
							a: 1,
							b: [1, 2]
						},
						
						foo,
						p
					],
					
					cloned = KM.clone(obj);
					
				obj[0].b[0] = 11;
					
				expect(cloned[0].b[0]).toEqual(1);
				expect(cloned[1]).toEqual(foo);
				expect(cloned[2]).toEqual(p);
			
			});
			
			
			it('could unlink the reference to its prototype', function(){
				var o3 = {
						a: {
							a: 1,
							b: 2,
							c: {
								d: {
									e: 1
								}
							}
						},
						
						b: {
							a: 11,
							b: 22
						}
					},
					
					receiver3;
					
				o3.c = o3;
					
				function O3(){};
				O3.prototype = o3;
					
				// receiver3 ------------------------------------
				// unlink the reference of an object
				receiver3 = new O3;
				KM.clone(receiver3, false, receiver3);
				
				// change the value of a property in receiver3
				receiver3.a.a = 11;
				expect( o3.a.a ).toEqual(1); // no affect with its prototype
				
				// the change of the prototype will not affect its instances
				o3.a.b = 22;
				expect( receiver3.a.b ).toEqual(2)
			
			});
			
			
			it('could use clone filter', function(){
				var o32 = {
						a: {
							a: 1,
							b: 2,
							c: {
								d: {
									e: 1
								}
							},
							
							d: {
								e: {
									f: 1
								}
							}
						},
						
						b: {
							a: 11,
							b: 22
						},
						
						d: {
							a: 11,
							b: 22
						}
					},
					
					receiver32;
					
				o32.c = o32;
					
				function O32(){};
				O32.prototype = o32;
				
				// !important
				// * receiver4 * ------------------------------------
				// test filter
				receiver32 = new O32;
				
				KM.clone(receiver32, function(v, k, d){
				
					// dont copy .b and depth less than 3
					return k !== 'b' && d < 3;
					
				}, receiver32);
				
				// the change of property 'b' will affect prototype
				receiver32.b.a = 111;
				expect( o32.b.a ).toEqual(111);
				
				// the change of prototype will affect receiver32
				o32.b.a = 1234;
				expect( receiver32.b.a ).toEqual(1234);
				
				// test clone depth
				o32.a.c = 1;
				
				// c is an object, and already be cloned
				expect( !KM.isNumber( receiver32.a.c ) ).toBeTruthy();
				
				// filter: k !== 'b'
				// o32.b.a = 111;
				// expect( receiver32.b.a ).toEqual(111);
				
				// o32.d.a = 111;
				// expect( receiver32.d.a ).toEqual(11);
			
			});	
		});
		
		
		describe('.bind()', function(){
			it('could bind a normal fn', function(){
				var context = {
						a: 1
					},
					
					obj = {
						a: 2,
						fn: function(){
							return this.a;
						}
					},
					
					fn;
					
				fn = KM.bind(obj.fn, context);
				expect(fn()).toEqual(1);
				
			});
			
			
			it('could bind a singleton method', function(){	
				var context = {
						a: 1
					},
					
					obj2 = {
						a: 3,
						fn: function(){
							return this.a;
						},
						
						bindFn: function(){
							KM.bind('fn', context);
						}
					};
			
				obj2.bindFn();
				expect(obj2.fn()).toEqual(3);
			});
				
		});
		
		
		describe('.makeArray()', function(){
			function checkArray(item){
				var converted = KM.makeArray(item);
				
				expect( KM.isArray( converted ) ).toBeTruthy();
			};
			
			it('will return the array itself', function(){
				checkArray([]);
			});
			
			it('will wrap non-array objects and numeric variables', function(){
				checkArray();
				checkArray(null);
				checkArray(123);
				checkArray('STRING');
				checkArray(window);
			});
			
			it('will make collections as pure array', function(){
				checkArray(document.getElementsByTagName('p'));
				checkArray(document.getElementsByTagName('select'));
				checkArray(document.getElementsByTagName('option'));
			});
		});
		
		
		describe('semi-private: ._overloadSetter()', function(){
			
			it('could overload a normal fn', function(){
		
				// test overload for normal function
				function setter(key, value){
					storage[key] = value;
				}
			
				var storage = {},
					batchSetter = KM._overloadSetter( setter );
				
				setter('a', 1);
				expect( storage.a ).toEqual(1);
				
				batchSetter({
					a: 2,
					b: 3
				});
				expect( storage.a ).toEqual(2);
				expect( storage.b ).toEqual(3);
			});
			
			
			it('could overload a singleton method', function(){	
				// test overload for singleton method
				var obj = {
						storage: {},
						setter: function(key, value){
							this.storage[key] = value;
						},
						
						change: function(){
							this.setter = KM._overloadSetter(this.setter);
						}
					};
					
				obj.setter('a', 1);
				expect( obj.storage.a ).toEqual(1);
				
				obj.change();
				
				obj.setter({
					a: 2,
					b: 3
				});
				expect( obj.storage.a ).toEqual(2);
				expect( obj.storage.b ).toEqual(3);
			});
		});
		
		
		describe('semi-private: ._onceBefore()', function(){
			it('could swap two methods', function(){
				var obj = {
						real: function(){
							return this.a;
						},
						
						fake: function(){
							this.a = 2;
						}
					};
					
				expect( obj.real() ).toBeUndefined();
				
				KM._onceBefore('real', 'fake', obj);
				
				expect( obj.real() ).toEqual(2);
			});
		});
		
		
		describe('semi-private: ._memoize()', function(){
			it('could memoize the result of a function with number params', function(){
				function foo(){
					exec_counter ++;
					
					return Array.prototype.join.call(arguments, '_');
				};
				
				var exec_counter = 1,
					memoized_foo = KM._memoize( foo );
				
				memoized_foo(1, 2, 3); // exec_counter:2
				
				expect( memoized_foo(1, 2, 3) ).toEqual('1_2_3');
				expect( exec_counter ).toEqual(2);
				
			});
		});
	});


});

</script>