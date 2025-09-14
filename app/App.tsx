// App.tsx
import { NavigationContainer } from '@react-navigation/native';
import { createStackNavigator } from '@react-navigation/stack';
import AddIngredient from '../app/screens/AddIngredient';
import InventoryMain from '../app/screens/Inventory';

const Stack = createStackNavigator();

function App() {
  return (
    <NavigationContainer>
      <Stack.Navigator initialRouteName="InventoryMain">
        <Stack.Screen name="InventoryMain" component={InventoryMain} />
        <Stack.Screen name="AddIngredient" component={AddIngredient} />
      </Stack.Navigator>
    </NavigationContainer>
  );
}

export default App;